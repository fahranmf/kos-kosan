<?php

class Sewa {
    // Fungsi untuk menampilkan semua status sewa
    public static function getAllStatusSewa(): array {
        $db = Database::getConnection();
        $query = "SELECT * FROM sewa ORDER BY id_sewa ASC";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
