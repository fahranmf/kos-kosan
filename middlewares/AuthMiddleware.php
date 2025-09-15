<?php
// middlewares/AuthMiddleware.php

class AuthMiddleware {
    public static function checkAdmin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak. Silakan login sebagai Admin.';
            header('Location: index.php?page=login');
            exit;
        }
    }

    public static function checkPenyewa() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'penyewa') {
            $_SESSION['error'] = 'Akses ditolak. Silakan login sebagai Penyewa.';
            header('Location: index.php?page=login');
            exit;
        }
    }
}
?>
