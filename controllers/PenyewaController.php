<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Feedback.php';
require_once 'models/Penyewa.php';
use Carbon\Carbon;

class PenyewaController
{
    public function dashboard()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $data = Penyewa::getProfilLengkap($id_penyewa);
        // Hitung sisa hari sewa
        if (!empty($data['tanggal_selesai'])) {
            $tanggalSelesai = Carbon::parse($data['tanggal_selesai']);
            $sekarang = Carbon::now();

            if ($sekarang->greaterThan($tanggalSelesai)) {
                $data['sisa_hari_jam'] = '0 hari 0 jam';
            } else {
                $diffInSeconds = $tanggalSelesai->timestamp - $sekarang->timestamp; // total detik sisa
                $diffInDays = floor($diffInSeconds / 86400); // 86400 detik per hari
                $remainingSeconds = $diffInSeconds % 86400;
                $diffInHours = floor($remainingSeconds / 3600); // 3600 detik per jam
                $data['sisa_hari_jam'] = $diffInDays . ' hari ' . $diffInHours . ' jam';
            }
        } else {
            $data['sisa_hari'] = null; // atau 0, sesuaikan
        }
        require_once 'views/penyewa/dashboard.php';
    }

    public function keluhan()
    {
        AuthMiddleware::checkPenyewa();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $no_kamar = $_SESSION['no_kamar'];
            $isi_feedback = $_POST['isi_feedback'] ?? '';
            Feedback::kirimFeedback($no_kamar, $isi_feedback);
            // Redirect setelah submit
            header('Location: index.php?page=penyewa_keluhan');
            exit();
        }
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;

        $no_kamar = $_SESSION['no_kamar'];
        $totalData = Feedback::getTotalKeluhanByNoKamar($no_kamar);
        $feedbackList = Feedback::getFeedbackByNoKamar($no_kamar, $limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        include 'views/penyewa/keluhan.php';
    }
    public function perpanjangKos()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $data = Penyewa::getProfilLengkap($id_penyewa);
        // Hitung sisa hari sewa
        if (!empty($data['tanggal_selesai'])) {
            $tanggalSelesai = Carbon::parse($data['tanggal_selesai']);
            $sekarang = Carbon::now();

            if ($sekarang->greaterThan($tanggalSelesai)) {
                $data['sisa_hari_jam'] = '0 hari 0 jam';
                $data['sisa_hari_int'] = 0;
            } else {
                $diffInSeconds = $tanggalSelesai->timestamp - $sekarang->timestamp;
                $diffInDays = floor($diffInSeconds / 86400);
                $remainingSeconds = $diffInSeconds % 86400;
                $diffInHours = floor($remainingSeconds / 3600);

                $data['sisa_hari_jam'] = $diffInDays . ' hari ' . $diffInHours . ' jam';
                $data['sisa_hari_int'] = $diffInDays;
            }
        } else {
            $data['sisa_hari_jam'] = null;
            $data['sisa_hari_int'] = 0;
        }

        include 'views/penyewa/perpanjang_kos.php';
    }

    public function historiPembayaran()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $pembayaranList = Penyewa::getDataPembayaranPenyewaById($id_penyewa);
        require_once 'views/penyewa/histori_pembayaran.php';
    }

       public function ubahNama()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $nama_baru = $_POST['nama_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $Password = penyewa::getPassword($id_penyewa);

        if ($password_konfirmasi == $Password) {
            penyewa::updateNama($id_penyewa, $nama_baru);
            $_SESSION['successMsg'] = "Nama berhasil diperbarui.";
        } else {
            $_SESSION['errorMsg'] = "Password salah. Gagal memperbarui nama.";
            $_SESSION['errorModal'] = "EditNama";
            header("Location: index.php?page=penyewa_dashboard#edit-nama");
            exit;
        }
        header('Location: index.php?page=penyewa_dashboard');
        exit;
    }

    public function ubahEmail()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $email_baru = $_POST['email_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $password_asli = penyewa::getPassword($id_penyewa);

        if ($password_konfirmasi !== $password_asli) {
            $_SESSION['errorMsg'] = "Password salah.";
            $_SESSION['errorModal'] = "EditEmail";
            header("Location: index.php?page=penyewa_dashboard#edit-email");
            exit;
        }

        // Generate OTP
        $otp = rand(100000, 999999);
        $_SESSION['otp_email_change'] = [
            'otp' => $otp,
            'email_baru' => $email_baru,
            'expiry' => time() + 300
        ];

        // Kirim OTP
        require_once 'helpers/send_mail.php';
        $sent = sendOTPEmail($email_baru, $otp);

        if ($sent) {
            $_SESSION['successMsg'] = "OTP dikirim ke email baru.";
        } else {
            $_SESSION['errorMsg'] = "Gagal mengirim OTP.";
            $_SESSION['errorModal'] = "EditEmail";
            header("Location: index.php?page=penyewa_dashboard#edit-email");
            exit;
        }

        header("Location: index.php?page=penyewa_dashboard#verifikasi-email");
        exit;
    }

    public function verifikasiEmailBaru()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $otp_input = trim($_POST['otp_kode'] ?? '');

        $otpData = $_SESSION['otp_email_change'] ?? null;

        if (empty($otp_input)) {
            $_SESSION['errorMsg'] = "OTP harus diisi.";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=penyewa_dashboard#verifikasi-email");
            exit;
        }

        if (!$otpData || time() > $otpData['expiry']) {
            $_SESSION['errorMsg'] = "OTP tidak valid atau kadaluarsa.";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=penyewa_dashboard#verifikasi-email");
            exit;
        }

        if ($otp_input !== $otpData['otp']) {
            $_SESSION['errorMsg'] = "OTP tidak valid!";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=penyewa_dashboard#verifikasi-email");
            exit;
        }

        penyewa::updateEmail($id_penyewa, $otpData['email_baru']);

        unset($_SESSION['otp_email_change']);
        $_SESSION['successMsg'] = "Email berhasil diperbarui.";
        header("Location: index.php?page=penyewa_dashboard");
        exit;
    }



    public function ubahTelp()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $telp_baru = $_POST['telp_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $Password = penyewa::getPassword($id_penyewa);

        if ($password_konfirmasi == $Password) {
            penyewa::updateTelp($id_penyewa, $telp_baru);
            $_SESSION['successMsg'] = "Nomor Telpon berhasil diperbarui.";
        } else {
            $_SESSION['errorMsg'] = "Password salah. Gagal memperbarui no telpon.";
            $_SESSION['errorModal'] = "EditTelp";
            header("Location: index.php?page=penyewa_dashboard#edit-telp");
            exit;
        }
        header('Location: index.php?page=penyewa_dashboard');
        exit;
    }

    public function ubahPassword()
    {
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi = $_POST['konfirmasi_password'];

        $Password = penyewa::getPassword($id_penyewa);

        if ($password_lama !== $Password) {
            $_SESSION['errorMsg'] = "Password lama salah.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=penyewa_dashboard#edit-pass");
            exit;
        } elseif ($password_baru !== $konfirmasi) {
            $_SESSION['errorMsg'] = "Konfirmasi password tidak cocok.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=penyewa_dashboard#edit-pass");
            exit;
        } elseif (strlen($password_baru) < 6) {
            $_SESSION['errorMsg'] = "Password baru minimal 6 karakter.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=penyewa_dashboard#edit-pass");
            exit;
        } else {
            penyewa::updatePassword($id_penyewa, $password_baru);
            $_SESSION['successMsg'] = "Password berhasil diperbarui.";
        }

        header('Location: index.php?page=penyewa_dashboard');
        exit;
    }

}
?>