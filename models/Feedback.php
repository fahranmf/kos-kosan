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
}
?>
