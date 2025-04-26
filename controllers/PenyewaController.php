<?php
require_once 'middlewares/AuthMiddleware.php';

class PenyewaController {
    public function dashboard() {
        AuthMiddleware::checkPenyewa(); // <--- cek dulu

        require_once 'views/penyewa/dashboard.php';
    }
}
?>
