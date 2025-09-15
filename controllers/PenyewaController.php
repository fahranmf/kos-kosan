<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Feedback.php';
require_once 'models/Penyewa.php';
require_once 'models/Pembayaran.php';
require_once 'helpers/AccessHelper.php';
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
        AccessHelper::blokirJikaCicilBelumLunas($_SESSION['user_id']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $no_kamar = $_SESSION['no_kamar'];
            $isi_feedback = $_POST['isi_feedback'] ?? '';
            $id_penyewa = $_SESSION['user_id'];
            Feedback::kirimFeedback($id_penyewa, $no_kamar, $isi_feedback);
            // Redirect setelah submit
            header('Location: index.php?page=penyewa_keluhan');
            exit();
        }
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;
        $id_penyewa = $_SESSION['user_id'];
        $no_kamar = $_SESSION['no_kamar'];
        $totalData = Feedback::getTotalKeluhanByPenyewaAndKamar($id_penyewa, $no_kamar);
        $feedbackList = Feedback::getFeedbackByPenyewaAndKamar($id_penyewa, $no_kamar, $limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        include 'views/penyewa/keluhan.php';
    }
    public function perpanjangKos()
    {
        AuthMiddleware::checkPenyewa();
        AccessHelper::blokirJikaCicilBelumLunas($_SESSION['user_id']);
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
        $data['kamar_kosong'] = Kamar::getKamarKosong();
        include 'views/penyewa/perpanjang_kos.php';
    }

    public function formPerpanjangKos()
    {
        AuthMiddleware::checkPenyewa();
        AccessHelper::blokirJikaCicilBelumLunas($_SESSION['user_id']);
        $id_sewa = $_POST['id_sewa'];
        $tanggal_baru = $_POST['tanggal_selesai_baru'];
        $ganti_kamar = isset($_POST['ganti_kamar']);
        $id_kamar_baru = $ganti_kamar ? $_POST['id_kamar_baru'] : null;

        if ($ganti_kamar && $id_kamar_baru) {
            $harga = Kamar::getHargaByNoKamar($id_kamar_baru);
        } else {
            $harga = Kamar::getHargaByIdSewa($id_sewa);
        }

        $_SESSION['perpanjang'] = [
            'id_sewa' => $id_sewa,
            'tanggal_baru' => $tanggal_baru,
            'ganti_kamar' => $ganti_kamar,
            'id_kamar_baru' => $id_kamar_baru,
            'harga' => $harga
        ];
        // Redirect ke halaman pembayaran khusus perpanjang
        header('Location: index.php?page=form_pembayaran_perpanjang');
        exit;
    }

    public function formPembayaranPerpanjang()
    {
        AuthMiddleware::checkPenyewa();
        AccessHelper::blokirJikaCicilBelumLunas($_SESSION['user_id']);

        if (!isset($_SESSION['perpanjang'])) {
            header('Location: index.php?page=dashboard'); // atau ke error page
            exit;
        }

        $data = $_SESSION['perpanjang'];
        require 'views/penyewa/form_pembayaran_perpanjang.php';
    }

    public function submitPembayaranPerpanjang()
    {
        AuthMiddleware::checkPenyewa();
        AccessHelper::blokirJikaCicilBelumLunas($_SESSION['user_id']);

        if (!isset($_SESSION['perpanjang'])) {
            $_SESSION['errorMsg'] = 'Data perpanjangan tidak valid.';
            header('Location: index.php?page=dashboard');
            exit;
        }

        $data = $_SESSION['perpanjang'];
        $id_sewa = $data['id_sewa'];
        $tanggal_baru = $data['tanggal_baru'];
        $ganti_kamar = $data['ganti_kamar'];
        $id_kamar_baru = $data['id_kamar_baru'];
        $jumlah_bayar = $_POST['jumlah_bayar'];
        $metode = $_POST['metode_pembayaran'];
        $tanggal_pembayaran = date('Y-m-d H:i:s');

        // Upload file bukti
        $bukti = null;
        if (!empty($_FILES['bukti_pembayaran']['name'])) {
            $targetDir = "uploads/bukti_pembayaran/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Ambil ekstensi file
            $originalName = $_FILES['bukti_pembayaran']['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Daftar ekstensi yang diizinkan
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array($ext, $allowed)) {
                $fileName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($originalName)); // sanitasi nama file
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $targetFile)) {
                    $bukti = $fileName; // berhasil upload
                }
            } else {
                echo "Format file tidak diizinkan. Hanya jpg, jpeg, png, atau pdf yang diperbolehkan.";
            }
        }



        // Insert ke tabel pembayaran
        $pembayaran = new Pembayaran();
        $insertBayar = $pembayaran->insertPembayaran([
            'id_sewa' => $id_sewa,
            'tanggal_pembayaran' => $tanggal_pembayaran,
            'jumlah_bayar' => $jumlah_bayar,
            'metode_pembayaran' => $metode,
            'bukti_pembayaran' => $bukti,
            'jenis_pembayaran' => 'Lunas',
            'tenggat_pembayaran' => null,
            'status_pembayaran' => 'Sedang Ditinjau',
            'tipe_pembayaran' => 'Perpanjang'
        ]);


        // Proses update sewa dan kamar
        if ($insertBayar) {
            $sewa = Sewa::getSewaByIdSewa($id_sewa);
            $tanggal_lama = $sewa['tanggal_selesai'];
            if ($ganti_kamar && $id_kamar_baru) {
                $kamar_lama = $sewa['no_kamar'];

                // Update sewa
                Sewa::updateTanggalDanKamar($id_sewa, $tanggal_baru, $id_kamar_baru, $tanggal_lama);

                // Update kamar status
                Kamar::setStatusKamar($kamar_lama, 'kosong');
                Kamar::setStatusKamar($id_kamar_baru, 'isi');
            } else {
                Sewa::updateTanggalSelesai($id_sewa, $tanggal_baru, $tanggal_lama);
            }
        }

        unset($_SESSION['perpanjang']);
        $_SESSION['successMsg'] = 'Pembayaran berhasil dikirim. Menunggu verifikasi admin.';
        header('Location: index.php?page=penyewa_riwayat');
        exit;
    }


    public function historiPembayaran()
    {
        AuthMiddleware::checkPenyewa();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;
        $id_penyewa = $_SESSION['user_id'];
        $totalData = Penyewa::getTotalDataPembayaranByIdPenyewa($id_penyewa);
        $pembayaranList = Penyewa::getDataPembayaranPenyewaById($id_penyewa, $limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/penyewa/histori_pembayaran.php';
    }

    public function pelunasanKos()
    {
        AccessHelper::blokirJikaBukanCicilanAktif($_SESSION['user_id']);
        AuthMiddleware::checkPenyewa();
        $id_penyewa = $_SESSION['user_id'];
        $sewa = Sewa::getSewaAktifByPenyewa($id_penyewa);
        if (!$sewa) {
            $_SESSION['errorMsg'] = 'Sewa aktif tidak ditemukan';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }

        $harga = Kamar::getHargaByNoKamar($sewa['no_kamar']); // ambil harga kamar
        $cicilan_terakhir = Pembayaran::getCicilanTerakhir($sewa['id_sewa']);
        $sisa = $harga - $cicilan_terakhir;

        // Cegah nilai minus
        if ($sisa <= 0) {
            $_SESSION['errorMsg'] = 'Tidak ada pelunasan yang perlu dibayar. Sudah lunas.';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }

        $data = [
            'harga' => $sisa
        ];

        include 'views/penyewa/pelunasan_kos.php';
    }

    public function submitPelunasan()
    {
        AuthMiddleware::checkPenyewa();
        AccessHelper::blokirJikaBukanCicilanAktif($_SESSION['user_id']);
        $id_penyewa = $_SESSION['user_id'];
        $sewa = Sewa::getSewaAktifByPenyewa($id_penyewa);

        if (!$sewa) {
            $_SESSION['errorMsg'] = 'Sewa tidak ditemukan';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }

        $id_sewa = $sewa['id_sewa'];

        // Proses pembayaran pelunasan
        $jumlah_bayar = $_POST['jumlah_bayar'];
        $metode = $_POST['metode_pembayaran'];
        // Upload file bukti
        $bukti = null;
        if (!empty($_FILES['bukti_pembayaran']['name'])) {
            $targetDir = "uploads/bukti_pembayaran/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            // Ambil ekstensi file
            $originalName = $_FILES['bukti_pembayaran']['name'];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Daftar ekstensi yang diizinkan
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array($ext, $allowed)) {
                $fileName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($originalName)); // sanitasi nama file
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $targetFile)) {
                    $bukti = $fileName; // berhasil upload
                } else {
                    echo "❌ Gagal upload file ke: " . $targetFile;
                    exit;
                }
            } else {
                echo "Format file tidak diizinkan. Hanya jpg, jpeg, png, atau pdf yang diperbolehkan.";
            }
        }

        $tanggal_pembayaran = date('Y-m-d H:i:s');

        $pembayaran = new Pembayaran();
        $result = $pembayaran->insertPembayaran([
            'id_sewa' => $id_sewa,
            'tanggal_pembayaran' => $tanggal_pembayaran,
            'jumlah_bayar' => $jumlah_bayar,
            'metode_pembayaran' => $metode,
            'bukti_pembayaran' => $bukti,
            'jenis_pembayaran' => 'Lunas',
            'tenggat_pembayaran' => null,
            'status_pembayaran' => 'Sedang Ditinjau',
            'tipe_pembayaran' => 'Pelunasan'
        ]);

        if ($result) {
            $_SESSION['successMsg'] = 'Pembayaran pelunasan berhasil dikirim.';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        } else {
            $_SESSION['errorMsg'] = 'Gagal menyimpan pembayaran ke database.';
            header('Location: index.php?page=penyewa_pelunasan_kos');
            exit;
        }

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