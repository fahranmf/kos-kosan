<?php
// controllers/AuthController.php

require_once 'models/Admin.php';
require_once 'models/Penyewa.php';

class AuthController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Cek login admin
            $admin = Admin::findByEmail($email);
            if ($admin && $password == $admin->password_admin) {
                $_SESSION['role'] = 'admin';
                $_SESSION['user_id'] = $admin->id_admin;

                require_once 'models/Sewa.php';
                $db = Database::getConnection();
                Sewa::cekDanUpdateSewaSelesai($db);

                header('Location: index.php?page=admin_dashboard');
                exit;
            }

            // Cek login penyewa
            $penyewa = Penyewa::findByEmail($email);
            if ($penyewa && $password == $penyewa->password_penyewa) {
                $no_kamar = Penyewa::getNoKamarByPenyewa($penyewa->email_penyewa);
                $penyewa->no_kamar = $no_kamar; // Tambahkan properti baru ke object               
                $_SESSION['role'] = 'penyewa';
                $_SESSION['user_id'] = $penyewa->id_penyewa;
                $_SESSION['nama_penyewa'] = $penyewa->nama_penyewa;


                if ($penyewa->status_akun == 'Terverifikasi') {
                    $_SESSION['no_kamar'] = $penyewa->no_kamar;
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
            header('Location: index.php?page=login');
            exit;
        }

        // Kalau method GET, tampilkan halaman login
        require_once 'views/public/login.php';
    }

    public function register()
    {
        // FORM 1: Kirim OTP
        if (isset($_POST['kirim_otp'])) {
            $email = $_POST['email_penyewa'] ?? '';

            if (empty($email)) {
                $_SESSION['error'] = 'Email tidak boleh kosong.';
                header('Location: index.php?page=register');
                exit;
            }

            if (Penyewa::findByEmail($email)) {
                $_SESSION['error'] = 'Email sudah terdaftar! Silakan login atau gunakan email lain.';
                header('Location: index.php?page=register');
                exit;
            }

            // Simpan email di session supaya tetap bisa ditampilkan di form lain
            $_SESSION['form_input'] = ['email_penyewa' => $email];

            // Generate OTP
            $otp = rand(100000, 999999);
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_email'] = $email;
            $_SESSION['otp_expiry'] = time() + 300; // OTP berlaku 5 menit

            require_once 'helpers/send_mail.php';
            $sent = sendOTPEmail($email, $otp);

            if ($sent) {
                $_SESSION['otp_sent'] = true;
                $_SESSION['success'] = 'OTP telah dikirim ke email ' . htmlspecialchars($email);
            } else {
                unset($_SESSION['otp_sent']);
                $_SESSION['error'] = 'Gagal mengirim OTP.';
            }

            header('Location: index.php?page=register');
            exit;
        }

        // FORM 2: Verifikasi OTP
        if (isset($_POST['verify_otp'])) {
            $otp_input = $_POST['otp'] ?? '';

            if (empty($otp_input)) {
                $_SESSION['error'] = 'OTP harus diisi.';
                header('Location: index.php?page=register');
                exit;
            }

            if (!isset($_SESSION['otp']) || time() > $_SESSION['otp_expiry']) {
                $_SESSION['error'] = 'OTP kadaluarsa!';
                header('Location: index.php?page=register');
                exit;
            }

            if ($otp_input != $_SESSION['otp']) {
                $_SESSION['error'] = 'OTP tidak valid!';
                header('Location: index.php?page=register');
                exit;
            }

            // OTP benar, tandai sudah verified
            $_SESSION['otp_verified'] = true;
            $_SESSION['success'] = 'OTP berhasil diverifikasi. Silakan lengkapi pendaftaran.';
            $_SESSION['form_input']['email_penyewa'] = $_SESSION['otp_email'];

            unset($_SESSION['otp_sent']);  // Hapus agar pesan OTP sent tidak muncul lagi

            header('Location: index.php?page=register');
            exit;
        }

        // FORM 3: Submit Register
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['kirim_otp']) && !isset($_POST['verify_otp'])) {
            $nama_penyewa = $_POST['nama_penyewa'] ?? '';
            $no_telp_penyewa = $_POST['no_telp_penyewa'] ?? '';
            $email_penyewa = $_POST['email_penyewa'] ?? '';
            $password_penyewa = $_POST['password_penyewa'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Simpan semua input ke session supaya kalau error bisa diisi ulang
            $_SESSION['form_input'] = $_POST;

            // Validasi form

            // Validasi semua field harus diisi
            if (empty($nama_penyewa) || empty($no_telp_penyewa) || empty($email_penyewa) || empty($password_penyewa) || empty($confirm_password)) {
                $_SESSION['error'] = 'Semua field harus diisi!';
                header('Location: index.php?page=register');
                exit;
            }

            if ($password_penyewa !== $confirm_password) {
                $_SESSION['error'] = 'Password dan Confirm Password tidak cocok!';
                $_SESSION['form_input'] = $_POST;
                header('Location: index.php?page=register');
                exit;
            }

            // Validasi OTP sudah diverifikasi
            if (!isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true || $email_penyewa !== ($_SESSION['otp_email'] ?? '')) {
                $_SESSION['error'] = 'Anda harus memverifikasi OTP terlebih dahulu dengan email yang sama.';
                header('Location: index.php?page=register');
                exit;
            }

            // Cek apakah email sudah terdaftar
            $penyewa = Penyewa::findByEmail($email_penyewa);
            if ($penyewa) {
                $_SESSION['error'] = 'Email sudah terdaftar!';
                $_SESSION['form_input'] = $_POST;
                unset($_SESSION['otp_sent']);
                header('Location: index.php?page=register');
                exit;
            }


            $penyewa = new Penyewa();
            $penyewa->nama_penyewa = $nama_penyewa;
            $penyewa->no_telp_penyewa = $no_telp_penyewa;
            $penyewa->email_penyewa = $email_penyewa;
            $penyewa->password_penyewa = $password_penyewa;
            $penyewa->status_akun = 'Umum'; // Default status akun
            $penyewa->registerPenyewa();

            $sent = sendRegisterSuccessEmail($email_penyewa);


            unset($_SESSION['otp'], $_SESSION['otp_email'], $_SESSION['otp_expiry'], $_SESSION['otp_sent'], $_SESSION['form_input']);

            // Redirect ke halaman login setelah sukses
            $_SESSION['success'] = 'Akun berhasil dibuat, silakan login!';
            header('Location: index.php?page=login');
            exit;
        }

        require_once 'views/public/register.php';
    }



    public function logout()
    {
        session_destroy();
        header('Location: index.php?page=home');
        exit;
    }
}
?>