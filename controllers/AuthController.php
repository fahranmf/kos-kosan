<?php
// controllers/AuthController.php

require_once 'models/Admin.php';
require_once 'models/Penyewa.php';

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Cek login admin
            $admin = Admin::findByEmail($email);
            if ($admin && $password == $admin->password_admin) { 
                $_SESSION['role'] = 'admin';
                $_SESSION['user_id'] = $admin->id_admin;
                header('Location: index.php?page=admin_dashboard');
                exit;
            }

            // Cek login penyewa
            $penyewa = Penyewa::findByEmail($email);
            if ($penyewa && $password == $penyewa->password_penyewa) { 
                $_SESSION['role'] = 'penyewa';
                $_SESSION['user_id'] = $penyewa->id_penyewa;

                if ($penyewa->status_akun == 'Terverifikasi') {
                    header('Location: index.php?page=penyewa_dashboard');
                } else if ($penyewa->status_akun == 'Menunggu Verifikasi') {
                    header('Location: index.php?page=home'); 
                } else {
                    header('Location: index.php?page=home');
                }
                exit;
            }

            // Jika login gagal
            $_SESSION['error'] = 'Email atau password salah!';
            header('Location: ../../index.php?page=login');
            exit;
        }

        // Kalau method GET, tampilkan halaman login
        require_once 'views/public/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_penyewa = $_POST['nama_penyewa'];
            $no_telp_penyewa = $_POST['no_telp_penyewa'];
            $email_penyewa = $_POST['email_penyewa'];
            $password_penyewa = $_POST['password_penyewa'];
            $confirm_password = $_POST['confirm_password'];
    
            // Validasi form
            if (empty($nama_penyewa) || empty($no_telp_penyewa) || empty($email_penyewa) || empty($password_penyewa) || empty($confirm_password)) {
                $_SESSION['error'] = 'Semua field harus diisi!';
                header('Location: index.php?page=register');
                exit;
            }
    
            if ($password_penyewa !== $confirm_password) {
                $_SESSION['error'] = 'Password dan Confirm Password tidak cocok!';
                header('Location: index.php?page=register');
                exit;
            }
    
            // Cek apakah email sudah terdaftar
            $penyewa = Penyewa::findByEmail($email_penyewa);
            if ($penyewa) {
                $_SESSION['error'] = 'Email sudah terdaftar!';
                header('Location: index.php?page=register');
                exit;
            }
    
    
            $penyewa = new Penyewa();
            $penyewa->nama_penyewa = $nama_penyewa;
            $penyewa->no_telp_penyewa = $no_telp_penyewa;
            $penyewa->email_penyewa = $email_penyewa;
            $penyewa->password_penyewa = $password_penyewa; 
            $penyewa->status_akun = 'Umum'; // Default status akun
            $penyewa->save();
    
            // Redirect ke halaman login setelah sukses
            $_SESSION['success'] = 'Akun berhasil dibuat, silakan login!';
            header('Location: index.php?page=login');
            exit;
        }
    
        require_once 'views/public/register.php';
    }
    
    

    public function logout() {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?>
