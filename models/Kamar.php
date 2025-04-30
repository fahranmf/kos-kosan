<?php
require_once 'config/database.php'; // Pastikan koneksi Database

class Kamar {
    // Dapatkan total kos
    public static function getTotalKos(): int {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM kamar";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    // Ambil semua data kamar
    public static function getAllKamar(): array {
        $db = Database::getConnection();
        $query = "SELECT * FROM kamar ORDER BY no_kamar ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari kamar berdasarkan ID
    public static function findById($id) {
        $db = Database::getConnection();
        $query = "SELECT * FROM kamar WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update data kamar
    public static function update($id, $foto_kos, $tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas) {
        $db = Database::getConnection();

        
        $query = "UPDATE kamar SET 
                    foto_kos = :foto_kos,
                    tipe_kamar = :tipe_kamar,
                    harga_perbulan = :harga_perbulan,
                    status = :status,
                    deskripsi = :deskripsi,
                    fasilitas = :fasilitas
                  WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':foto_kos', $foto_kos);
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':harga_perbulan', $harga_perbulan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':fasilitas', $fasilitas);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Tambah Kamar
    public static function insertKamar($tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas, $foto_kos) {
        $db = Database::getConnection();
    
        // Query untuk menambah kamar baru ke database
        $query = "INSERT INTO kamar (tipe_kamar, harga_perbulan, status, deskripsi, fasilitas, foto_kos) 
                  VALUES (:tipe_kamar, :harga_perbulan, :status, :deskripsi, :fasilitas, :foto_kos)";
        $stmt = $db->prepare($query);
        
        // Bind parameter
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':harga_perbulan', $harga_perbulan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':fasilitas', $fasilitas);
        $stmt->bindParam(':foto_kos', $foto_kos);
    
        // Eksekusi query
        $stmt->execute();
    }
    
    // Hapus kamar
    public static function deleteById($id) {
        $db = Database::getConnection();
        $query = "DELETE FROM kamar WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
?>
