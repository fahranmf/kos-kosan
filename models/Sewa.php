<?php

class Sewa
{
    // Fungsi untuk menampilkan semua status sewa
    public static function getAllStatusSewa(): array
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM sewa ORDER BY id_sewa ASC";
        $stmt = $db->prepare($query);
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






}
?>