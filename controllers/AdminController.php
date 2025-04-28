<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Kamar.php';  
require_once 'models/Penyewa.php';  
require_once 'models/Feedback.php';  

class AdminController {
    // Menampilkan halaman Dashboard Admin
    public function dashboard() {

        AuthMiddleware::checkAdmin(); // <--- cek dulu
        
        // Ambil data statistik yang diperlukan
        // Contoh: jumlah kos, jumlah penyewa, jumlah keluhan, dll
        $totalKos = Kamar::getTotalKos(); // Menampilkan jumlah kos
        $totalPenyewa = Penyewa::getTotalPenyewa(); // Menampilkan jumlah penyewa
        $totalKeluhan = Feedback::getTotalKeluhan(); // Menampilkan jumlah keluhan
        
        // Panggil view dashboard.php dan kirimkan data
        require_once 'views/admin/dashboard.php';
    }
}
?>
