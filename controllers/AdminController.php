<?php
require_once 'middlewares/AuthMiddleware.php';

class AdminController {
    public function dashboard() {
        AuthMiddleware::checkAdmin(); // <--- cek dulu

        require_once 'views/admin/dashboard.php';
    }
}
?>
