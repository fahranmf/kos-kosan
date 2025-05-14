<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Kamar.php';
require_once 'models/Penyewa.php';
require_once 'models/Feedback.php';
require_once 'models/Pembayaran.php';
require_once 'models/Sewa.php';

class PublicController {
    // Method untuk home
    public function home() {
        $kamarList = Kamar::getKamarPerTipeDenganJumlahKosong();
        require_once 'views/public/home.php';
    }

    public function bookingKamar($tipe_kamar) {
        AuthMiddleware::checkPenyewa();
    }
    
}
