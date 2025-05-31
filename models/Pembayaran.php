<?php
require_once 'config/database.php'; // Pastikan koneksi Database

class Pembayaran
{
    // Fungsi untuk mendapatkan total jumlah keluhan
    public static function getAllPembayaran(): array
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM pembayaran ORDER BY id_pembayaran ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insertPembayaran($data)
    {
        $db = Database::getConnection();

        $query = "INSERT INTO pembayaran (
        id_sewa, tanggal_pembayaran, jumlah_bayar, metode_pembayaran,
        bukti_pembayaran, jenis_pembayaran, tenggat_pembayaran, status_pembayaran
    ) VALUES (
        :id_sewa, :tanggal_pembayaran, :jumlah_bayar, :metode_pembayaran,
        :bukti_pembayaran, :jenis_pembayaran, :tenggat_pembayaran, :status_pembayaran
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
        ]);
    }

}
?>