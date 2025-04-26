<?php
// Start session
session_start();

// Autoload semua file penting
require_once 'config/database.php';  // Koneksi database
require_once 'controllers/AuthController.php';  // Controller untuk login, register, logout
require_once 'controllers/AdminController.php';  // Controller untuk admin
require_once 'controllers/PenyewaController.php';  // Controller untuk penyewa
require_once 'controllers/PublicController.php';  // Controller untuk tampilan umum

// Routing sederhana manual
$page = $_GET['page'] ?? 'home';

// --- Routing untuk halaman Public ---
if ($page == 'home') {
    $controller = new PublicController();
    $controller->home();
} elseif ($page == 'login') {
    $controller = new AuthController();
    $controller->login();
} elseif ($page == 'register') {
    $controller = new AuthController();
    $controller->register(); // Nanti buat register ya
} elseif ($page == 'logout') {
    $controller = new AuthController();
    $controller->logout();
} elseif ($page == 'daftar_kos') {
    $controller = new PublicController();
    $controller->daftarKos();
} elseif ($page == 'detail_kos') {
    $controller = new PublicController();
    $controller->detailKos($_GET['id']);
} elseif ($page == 'cek_status') {
    $controller = new PublicController();
    $controller->cekStatus();
}

// --- Routing untuk Admin ---
elseif ($page == 'admin_dashboard') {
    if ($_SESSION['role'] == 'admin') {
        $controller = new AdminController();
        $controller->dashboard();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'admin_daftar_kos') {
    if ($_SESSION['role'] == 'admin') {
        $controller = new AdminController();
        $controller->daftarKos();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'admin_data_penyewa') {
    if ($_SESSION['role'] == 'admin') {
        $controller = new AdminController();
        $controller->dataPenyewa();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'admin_keluhan') {
    if ($_SESSION['role'] == 'admin') {
        $controller = new AdminController();
        $controller->keluhan();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'admin_verifikasi') {
    if ($_SESSION['role'] == 'admin') {
        $controller = new AdminController();
        $controller->verifikasi();
    } else {
        header('Location: index.php?page=login');
    }
}

// --- Routing untuk Penyewa ---
elseif ($page == 'penyewa_dashboard') {
    if ($_SESSION['role'] == 'penyewa') {
        $controller = new PenyewaController();
        $controller->dashboard();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'penyewa_histori') {
    if ($_SESSION['role'] == 'penyewa') {
        $controller = new PenyewaController();
        $controller->historiPembayaran();
    } else {
        header('Location: index.php?page=login');
    }
} elseif ($page == 'penyewa_keluhan') {
    if ($_SESSION['role'] == 'penyewa') {
        $controller = new PenyewaController();
        $controller->keluhan();
    } else {
        header('Location: index.php?page=login');
    }
}

// --- Jika halaman tidak ditemukan ---
else {
    echo "404 Not Found";
}
?>
