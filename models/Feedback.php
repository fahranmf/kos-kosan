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

    
    
}
?>
