<?php
class Kamar {
    // Fungsi untuk mendapatkan total jumlah kos
    public static function getTotalKos(): int {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM kamar";  // Ganti sesuai dengan nama tabel kos
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }
}
?>
