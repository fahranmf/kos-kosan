<?php
require_once 'config/database.php'; // Pastikan koneksi Database

class Pembayaran {
    // Fungsi untuk mendapatkan total jumlah keluhan
    public static function getAllPembayaran(): array {
        $db = Database::getConnection();
        $query = "SELECT * FROM pembayaran ORDER BY id_pembayaran ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
