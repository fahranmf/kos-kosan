<?php
// models/Penyewa.php

require_once 'config/database.php';

class Penyewa
{
    public string $nama_penyewa;
    public string $no_telp_penyewa;
    public string $email_penyewa;
    public string $password_penyewa;
    public string $status_akun;


    public static function findByEmail($email)
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM penyewa WHERE email_penyewa = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    public static function getNoKamarByPenyewa($email)
    {
        $db = Database::getConnection();
        $query = "SELECT sewa.no_kamar FROM penyewa
                  LEFT JOIN sewa ON penyewa.id_penyewa = sewa.id_penyewa
                  WHERE email_penyewa = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn(); // kalau hanya ambil satu kolom: no_kamar
    }

    // method input register ke tabel penyewa
    public function registerPenyewa(): void
    {
        $db = Database::getConnection();
        $query = "INSERT INTO penyewa (nama_penyewa, no_telp_penyewa, email_penyewa, password_penyewa, status_akun)
                  VALUES (:nama_penyewa, :no_telp_penyewa, :email_penyewa, :password_penyewa, :status_akun)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nama_penyewa', $this->nama_penyewa);
        $stmt->bindParam(':no_telp_penyewa', $this->no_telp_penyewa);
        $stmt->bindParam(':email_penyewa', $this->email_penyewa);
        $stmt->bindParam(':password_penyewa', $this->password_penyewa);
        $stmt->bindParam(':status_akun', $this->status_akun);
        $stmt->execute();
    }

    public static function getTotalPenyewa(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM penyewa  WHERE status_akun = 'Terverifikasi'";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    public static function getAllPenyewa(int $limit, int $offset): array
    {
        $db = Database::getConnection();
        $query = "SELECT id_penyewa, nama_penyewa, no_telp_penyewa, email_penyewa
                    FROM penyewa
                    WHERE status_akun = 'Terverifikasi'
                    LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getAllAkun(int $limit, int $offset): array
    {
        $db = Database::getConnection();
        $query = "SELECT 
                     *,
                    penyewa.id_penyewa,
                    penyewa.email_penyewa,
                    penyewa.status_akun
                FROM 
                    pembayaran
                JOIN 
                    sewa ON pembayaran.id_sewa = sewa.id_sewa
                JOIN 
                    penyewa ON sewa.id_penyewa = penyewa.id_penyewa
                WHERE 
                    penyewa.status_akun IN ('Terverifikasi' , 'Menunggu Verifikasi')
                ORDER BY pembayaran.tanggal_pembayaran DESC
                LIMIT :limit OFFSET :offset
        ";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalVerifPenyewa(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total
                FROM 
                    pembayaran
                JOIN 
                    sewa ON pembayaran.id_sewa = sewa.id_sewa
                JOIN 
                    penyewa ON sewa.id_penyewa = penyewa.id_penyewa
                WHERE 
                    penyewa.status_akun IN ('Terverifikasi', 'Menunggu Verifikasi');
                ";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    public static function updateStatusPembayaranDanAkun($id_pembayaran, $status_pembayaran, $id_penyewa)
    {
        $db = Database::getConnection();

        // Update status pembayaran
        $query1 = "UPDATE pembayaran p
               JOIN sewa s ON p.id_sewa = s.id_sewa
               SET p.status_pembayaran = :status_pembayaran
               WHERE p.id_pembayaran = :id_pembayaran";

        $stmt1 = $db->prepare($query1);
        $stmt1->execute([
            'id_pembayaran' => $id_pembayaran,
            'status_pembayaran' => $status_pembayaran
        ]);

        // Tentukan status akun
        if ($status_pembayaran === 'Terverifikasi') {
            $status_akun = 'Terverifikasi';
        } elseif ($status_pembayaran === 'Ditolak') {
            $status_akun = 'Umum';
        } else {
            $status_akun = 'Menunggu Verifikasi';
        }

        // Update status akun penyewa
        $query2 = "UPDATE penyewa SET status_akun = :status_akun WHERE id_penyewa = :id_penyewa";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([
            'status_akun' => $status_akun,
            'id_penyewa' => $id_penyewa
        ]);
    }




    public static function updateStatusAkun($id, $status): void
    {
        $db = Database::getConnection();
        $query = "UPDATE penyewa SET status_akun = :status WHERE id_penyewa = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public static function sudahAdaOrderAktif($id_penyewa)
    {
        $db = Database::getConnection();

        $sql = "SELECT COUNT(*) AS jumlah_order
                FROM sewa s
                LEFT JOIN pembayaran p ON p.id_sewa = s.id_sewa
                WHERE s.id_penyewa = ? 
                  AND (s.status_sewa = 'Sewa' OR p.jenis_pembayaran = 'Lunas')";

        $stmt = $db->prepare($sql);
        $stmt->execute([$id_penyewa]);
        $row = $stmt->fetch();

        return $row['jumlah_order'] > 0;
    }

    public static function getProfilLengkap($id_penyewa)
    {
        $db = Database::getConnection();
        $query = "SELECT 
                *,
                k.tipe_kamar, k.no_kamar,
                s.tanggal_mulai, s.tanggal_selesai, s.status_sewa
              FROM penyewa p
              LEFT JOIN sewa s ON p.id_penyewa = s.id_penyewa AND s.status_sewa = 'Sewa'
              LEFT JOIN kamar k ON s.no_kamar = k.no_kamar
              WHERE p.id_penyewa = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_penyewa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getDataPembayaranPenyewaById($id_penyewa): array
    {
        $db = Database::getConnection();
        $query = "SELECT 
					*,
                    sewa.no_kamar
                FROM 
                    pembayaran
                JOIN 
                    sewa ON pembayaran.id_sewa = sewa.id_sewa
                JOIN 
                    penyewa ON sewa.id_penyewa = penyewa.id_penyewa

                WHERE penyewa.id_penyewa = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_penyewa]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateNama($id_penyewa, $nama_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE penyewa SET nama_penyewa = ? WHERE id_penyewa = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$nama_baru, $id_penyewa]);
    }

    public static function updateEmail($id_penyewa, $email_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE penyewa SET email_penyewa = ? WHERE id_penyewa = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$email_baru, $id_penyewa]);
    }

    public static function updateTelp($id_penyewa, $telp_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE penyewa SET no_telp_penyewa = ? WHERE id_penyewa = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$telp_baru, $id_penyewa]);
    }

    public static function getPassword($id_penyewa)
    {
        $db = Database::getConnection();
        $query = "SELECT password_penyewa FROM penyewa WHERE id_penyewa = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_penyewa]);
        return $stmt->fetchColumn();
    }

    public static function updatePassword($id_penyewa, $password_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE penyewa SET password = ? WHERE id_penyewa = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$password_baru, $id_penyewa]);
    }
}
