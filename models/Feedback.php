<?php

class Feedback {
    // Fungsi untuk mendapatkan total jumlah keluhan
    public static function getTotalKeluhan(): int {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM feedback";  
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    public static function getAllKeluhan(): array {
        $db = Database::getConnection();
        $query = "SELECT * FROM feedback ORDER BY id_feedback ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function editStatus($id, $status): void {
        $db = Database::getConnection();
        $query = "UPDATE feedback SET status_feedback = :status WHERE id_feedback = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // method untuk mengirimkan / input keluhan 
    public static function kirimFeedback($no_kamar, $isi_feedback): bool {
        // Ambil koneksi database
        $db = Database::getConnection();

        //query 
        $query = "INSERT INTO feedback (no_kamar, tanggal_feedback, isi_feedback, status_feedback) 
                  VALUES (:no_kamar, NOW(), :isi_feedback, 'Belum Dibaca')";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':no_kamar', $no_kamar, PDO::PARAM_INT);
        $stmt->bindParam(':isi_feedback', $isi_feedback, PDO::PARAM_STR);
        return $stmt->execute();
    }
    
    // method untuk menampilkan / output nya berdasarkan no_kamar dari penyewa 
    public static function getFeedbackByNoKamar($no_kamar):array {
        $db = Database::getConnection();
        $query = "SELECT * FROM feedback WHERE no_kamar = ? ORDER BY tanggal_feedback DESC";
        $stmt = $db->prepare($query);
        $stmt->execute([$no_kamar]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    
    
    
    
    
}
?>
