<?php

use Carbon\Carbon;

class Sewa
{
    public static function cekDanUpdateSewaSelesai($db)
    {
        $today = Carbon::now();

        $query = $db->query("SELECT * FROM sewa WHERE status_sewa = 'Sewa'");
        $sewas = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($sewas as $sewa) {
            $tanggalSelesai = Carbon::parse($sewa['tanggal_selesai']);

            if ($tanggalSelesai->lessThanOrEqualTo($today)) {
                $id_sewa = $sewa['id_sewa'];
                $id_penyewa = $sewa['id_penyewa'];
                $no_kamar = $sewa['no_kamar'];

                // Update status_sewa
                $stmt = $db->prepare("UPDATE sewa SET status_sewa = 'Selesai' WHERE id_sewa = ?");
                $stmt->execute([$id_sewa]);

                // Update status_akun penyewa
                $stmt = $db->prepare("UPDATE penyewa SET status_akun = 'Umum' WHERE id_penyewa = ?");
                $stmt->execute([$id_penyewa]);

                // Update status kamar
                $stmt = $db->prepare("UPDATE kamar SET status = 'Kosong' WHERE no_kamar = ?");
                $stmt->execute([$no_kamar]);
            }
        }
    }

    public static function getTotalSewa(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM sewa";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }


    // Fungsi untuk menampilkan semua status sewa
    public static function getAllStatusSewa(int $limit, int $offset): array
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM sewa ORDER BY id_sewa ASC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function insertSewa($id_penyewa, $no_kamar, $tanggal_mulai, $tanggal_selesai)
    {
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Insert sewa
            $query = "INSERT INTO sewa (id_penyewa, no_kamar, tanggal_mulai, tanggal_selesai)
                  VALUES (:id_penyewa, :no_kamar, :tanggal_mulai, :tanggal_selesai)";
            $stmt = $db->prepare($query);
            $stmt->execute([
                ':id_penyewa' => $id_penyewa,
                ':no_kamar' => $no_kamar,
                ':tanggal_mulai' => $tanggal_mulai,
                ':tanggal_selesai' => $tanggal_selesai
            ]);

            // Ambil lastInsertId dulu sebelum commit
            $lastId = $db->lastInsertId();

            // Update status kamar jadi 'Isi'
            $updateQuery = "UPDATE kamar SET status = 'Isi' WHERE no_kamar = :no_kamar";
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([':no_kamar' => $no_kamar]);

            $db->commit();

            return $lastId;
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Error insertSewa: " . $e->getMessage());
            return false;
        }
    }

    public static function getJumlahSewaPerBulanByTahun($tahun)
    {
        $db = Database::getConnection();
        $query = "SELECT 
                MONTH(tanggal_mulai) AS bulan,
                COUNT(*) AS jumlah
              FROM sewa
              WHERE YEAR(tanggal_mulai) = :tahun
              GROUP BY bulan
              ORDER BY bulan";
        $stmt = $db->prepare($query);
        $stmt->execute(['tahun' => $tahun]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSewaAktifByPenyewa($id_penyewa)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM sewa WHERE id_penyewa = ? AND status_sewa = 'Sewa'");
        $stmt->execute([$id_penyewa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getSewaByIdSewa($id_sewa)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM sewa WHERE id_sewa = ?");
        $stmt->execute([$id_sewa]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateTanggalSelesai($id_sewa, $tanggal_baru, $tanggal_lama)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE sewa 
        SET tanggal_selesai_lama = ?, 
            tanggal_selesai = ? 
        WHERE id_sewa = ?");
        return $stmt->execute([$tanggal_lama, $tanggal_baru, $id_sewa]);
    }


    public static function updateTanggalDanKamar($id_sewa, $tanggal_baru, $no_kamar_baru, $tanggal_lama)
    {
        $db = Database::getConnection();
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE sewa 
        SET tanggal_selesai = ?, 
            tanggal_selesai_lama = ?, 
            no_kamar = ? 
        WHERE id_sewa = ?");
        return $stmt->execute([$tanggal_baru, $tanggal_lama, $no_kamar_baru, $id_sewa]);
    }








}
?>