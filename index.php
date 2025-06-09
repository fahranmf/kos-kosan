<?php
date_default_timezone_set('Asia/Jakarta');
// Mulai session
session_start();

// Load semua file penting
require_once 'config/database.php';        // Koneksi database
require_once 'controllers/AuthController.php';    // Controller Auth (Login, Register, Logout)
require_once 'controllers/AdminController.php';   // Controller Admin
require_once 'controllers/PenyewaController.php'; // Controller Penyewa
require_once 'controllers/PublicController.php';  // Controller Public (tampilan umum)

// Function untuk cek role user
function checkRole($requiredRole)
{
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $requiredRole) {
        header('Location: index.php?page=login');
        exit;
    }
}

// Ambil parameter 'page', default ke 'home'
$page = $_GET['page'] ?? 'home';

if (
    isset($_SESSION['otp']) &&
    !in_array($page, ['register', 'login'])
) {
    // Hapus OTP jika user belum selesai registrasi (belum submit form)
    unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry'], $_SESSION['otp_sent'], $_SESSION['form_input'], $_SESSION['otp_verified']);
}

// Routing menggunakan switch-case
switch ($page) {
    // --- Routing Public ---
    case 'home':
        $controller = new PublicController();
        $controller->home();
        break;

    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'register':
        $controller = new AuthController();
        $controller->register();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'booking_kamar':
        checkRole('penyewa');
        $controller = new PublicController();
        $tipe_kamar = $_POST['tipe_kamar'];
        $controller->bookingKamar();
        break;

    case 'pembayaran':
        checkRole('penyewa');
        $controller = new PublicController();
        $controller->prosesPembayaran();
        break;

    case 'detail_kamar':
        $controller = new PublicController();
        ;
        $tipe_kamar = $_GET['tipe'];
        $controller->detailKamar($tipe_kamar);
        break;

    case 'cek_status':
        $controller = new PublicController();
        $controller->cekStatus();
        break;

    // --- Routing Admin ---
    case 'admin_dashboard':
        checkRole('admin');
        $controller = new AdminController();
        $controller->dashboard();
        break;

    case 'admin_daftar_kos':
        checkRole('admin');
        $controller = new AdminController();
        $controller->daftarKos();
        break;

    case 'admin_data_penyewa':
        checkRole('admin');
        $controller = new AdminController();
        $controller->dataPenyewa();
        break;

    case 'admin_data_pembayaran':
        checkRole('admin');
        $controller = new AdminController();
        $controller->dataPembayaran();
        break;

    case 'admin_status_sewa':
        checkRole('admin');
        $controller = new AdminController();
        $controller->dataStatusSewa();
        break;

    case 'admin_keluhan':
        checkRole('admin');
        $controller = new AdminController();
        $controller->keluhan();
        break;

    case 'update_status_feedback':
        checkRole('admin');
        $controller = new AdminController();
        $controller->updateStatus();

    case 'admin_verifikasi':
        checkRole('admin');
        $controller = new AdminController();
        $controller->verifikasi();
        break;

    case 'admin_update_status_akun':
        checkRole('admin');
        $controller = new AdminController();
        $controller->editStatusAkun();
        break;

    case 'edit_kamar':
        checkRole('admin');
        $controller = new AdminController();
        $id = $_GET['id'];
        $controller->editKamar($id);
        break;

    case 'update_kamar':
        checkRole('admin');
        $controller = new AdminController();
        $controller->updateKamar();
        break;

    case 'tambah_kamar':
        checkRole('admin');
        $controller = new AdminController();
        $controller->tambahKamar();
        break;

    case 'hapus_kamar':
        checkRole('admin');
        $controller = new AdminController();
        $id = $_GET['id'];
        $controller->hapusKamar($id);
        break;

    case 'admin_profile':
        checkRole('admin');
        $controller = new AdminController();
        $controller->lihatProfile();
        break;

    case 'ubah_nama':
        checkRole('admin');
        $controller = new AdminController();
        $controller->ubahNama();
        break;

    case 'ubah_email':
        checkRole('admin');
        $controller = new AdminController();
        $controller->ubahEmail();
        break;

    case 'ubah_telp':
        checkRole('admin');
        $controller = new AdminController();
        $controller->ubahTelp();
        break;

    case 'ubah_password':
        checkRole('admin');
        $controller = new AdminController();
        $controller->ubahPassword();
        break;

    case 'verifikasi_email_baru':
        checkRole('admin');
        $controller = new AdminController();
        $controller->verifikasiEmailBaru();
        break;


    // --- Routing Penyewa ---
    case 'penyewa_dashboard':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->dashboard();
        break;

    case 'penyewa_riwayat':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->historiPembayaran();
        break;

    case 'penyewa_keluhan':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->keluhan();
        break;

    case 'penyewa_perpanjang_kos':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->perpanjangKos();
        break;

    case 'penyewa_ubah_nama':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->ubahNama();
        break;

    case 'penyewa_ubah_email':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->ubahEmail();
        break;

    case 'penyewa_ubah_telp':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->ubahTelp();
        break;

    case 'penyewa_ubah_password':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->ubahPassword();
        break;

    case 'penyewa_verifikasi_email_baru':
        checkRole('penyewa');
        $controller = new PenyewaController();
        $controller->verifikasiEmailBaru();
        break;

    // --- Halaman tidak ditemukan ---
    default:
        $controller = new PublicController();
        $controller->default();
        break;
}
?>