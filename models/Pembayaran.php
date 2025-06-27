<?php
require_once 'config/database.php'; // Pastikan koneksi Database

class Pembayaran
{
    // Fungsi untuk mendapatkan total jumlah keluhan
    public static function getAllPembayaran(int $limit, int $offset): array
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM pembayaran ORDER BY id_pembayaran DESC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getTotalPembayaran(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM pembayaran";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function insertPembayaran($data)
    {
        $db = Database::getConnection();

        $query = "INSERT INTO pembayaran (
        id_sewa, tanggal_pembayaran, jumlah_bayar, metode_pembayaran,
        bukti_pembayaran, jenis_pembayaran, tenggat_pembayaran, status_pembayaran, tipe_pembayaran
    ) VALUES (
        :id_sewa, :tanggal_pembayaran, :jumlah_bayar, :metode_pembayaran,
        :bukti_pembayaran, :jenis_pembayaran, :tenggat_pembayaran, :status_pembayaran, :tipe_pembayaran
    )";

        $stmt = $db->prepare($query);

        return $stmt->execute([
            ':id_sewa' => $data['id_sewa'],
            ':tanggal_pembayaran' => $data['tanggal_pembayaran'],
            ':jumlah_bayar' => $data['jumlah_bayar'],
            ':metode_pembayaran' => $data['metode_pembayaran'],
            ':bukti_pembayaran' => $data['bukti_pembayaran'],
            ':jenis_pembayaran' => $data['jenis_pembayaran'],
            ':tenggat_pembayaran' => $data['tenggat_pembayaran'],
            ':status_pembayaran' => $data['status_pembayaran'] ?? 'Sedang Ditinjau',
            ':tipe_pembayaran' => $data['tipe_pembayaran']
        ]);
    }

    public static function getCicilanTerakhir($id_sewa)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
        SELECT jumlah_bayar 
        FROM pembayaran 
        WHERE id_sewa = ? 
        AND jenis_pembayaran = 'cicil' 
        ORDER BY tanggal_pembayaran DESC 
        LIMIT 1
    ");
        $stmt->execute([$id_sewa]);
        $row = $stmt->fetch();
        return $row ? (int) $row['jumlah_bayar'] : 0;
    }
    public static function getTerakhirBySewa($id_sewa)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
        SELECT * FROM pembayaran 
        WHERE id_sewa = ? 
        ORDER BY tanggal_pembayaran DESC 
        LIMIT 1
        ");
        $stmt->execute([$id_sewa]);
        return $stmt->fetch();
    }
}
?>