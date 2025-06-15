<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Kamar.php';
require_once 'models/Penyewa.php';
require_once 'models/Feedback.php';
require_once 'models/Pembayaran.php';
require_once 'models/Sewa.php';
require_once 'vendor/autoload.php';

use Carbon\Carbon;

class PublicController
{
    // Method untuk home
    public function home()
    {
        // Cek apakah ini request AJAX buat dropdown kamar kosong
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipe_kamar'])) {
            $tipe_kamar = $_POST['tipe_kamar'];
            $kamarKosong = Kamar::getAvailableRoomByType($tipe_kamar);
            echo json_encode($kamarKosong);
            return; // biar ga lanjut ke require_once view
        }

        // Ini bagian default buat nampilin halaman
        $kamarList = Kamar::getKamarPerTipeDenganJumlahKosong();

        // Ambil data statistik
        $totalKos = Kamar::getTotalKos();
        $totalPenyewa = Penyewa::getTotalPenyewa();
        $totalTipeKamar = Kamar::getTipeKamar();
        require_once 'views/public/home.php';
    }

    public function bookingKamar()
    {
        AuthMiddleware::checkPenyewa();

        $id_penyewa = $_SESSION['user_id'];

        if (Penyewa::sudahAdaOrderAktif($id_penyewa)) {
            $_SESSION['errorMsg'] = "Kamu sudah melakukan booking sebelumnnya, tidak bisa booking kamar lagi.";
            $_SESSION['lastTipeKamar'] = $_POST['tipe_kamar'] ?? '';
            header("Location: index.php?page=home&openModal=1#rooms");
            exit();

        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tanggal_mulai = $_POST['tanggal_mulai'];

            // Hitung tanggal selesai 1 bulan dari tanggal mulai
            $carbonMulai = Carbon::parse($tanggal_mulai);
            $carbonSelesai = $carbonMulai->copy()->addMonth(); // atau ->addMonthsNoOverflow(1) biar aman dari 31 ke 30

            $_SESSION['booking'] = [
                'id_penyewa' => $_SESSION['user_id'],
                'no_kamar' => $_POST['no_kamar'],
                'tanggal_mulai' => $carbonMulai->format('Y-m-d'),
                'tanggal_selesai' => $carbonSelesai->format('Y-m-d')
            ];

            header("Location: index.php?page=pembayaran");
            exit();
        }
    }



    public function prosesPembayaran()
    {
        AuthMiddleware::checkPenyewa();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bayar'])) {
            if (!isset($_SESSION['booking'])) {
                $_SESSION['errorMsg'] = "Data booking tidak ditemukan.";
                header("Location: index.php?page=pembayaran");
                exit();
            }

            $dataBooking = $_SESSION['booking'];

            // Insert booking ke tabel sewa
            $id_sewa = Sewa::insertSewa(
                $dataBooking['id_penyewa'],
                $dataBooking['no_kamar'],
                $dataBooking['tanggal_mulai'],
                $dataBooking['tanggal_selesai']
            );

            if (!$id_sewa) {
                $_SESSION['errorMsg'] = "Gagal menyimpan data sewa.";
                header("Location: index.php?page=pembayaran");
                exit();
            }

            // Ambil harga kamar berdasarkan no_kamar dari session
            $harga = Kamar::getHargaByNoKamar($dataBooking['no_kamar']);
            if (!$harga) {
                $_SESSION['errorMsg'] = "Harga kamar tidak ditemukan.";
                header("Location: index.php?page=pembayaran");
                exit();
            }

            // Hitung jumlah bayar berdasarkan status pembayaran
            $jenis_pembayaran = $_POST['jenis_pembayaran'];
            if ($jenis_pembayaran === 'Lunas') {
                $jumlah_bayar = $harga;
            } elseif ($jenis_pembayaran === 'Cicil') {
                $jumlah_bayar = $harga / 2;
            } else {
                $_SESSION['errorMsg'] = "Status pembayaran tidak valid.";
                header("Location: index.php?page=pembayaran");
                exit();
            }

            // Upload file bukti pembayaran
            $bukti = null;
            if (!empty($_FILES['bukti_pembayaran']['name'])) {
                $targetDir = "uploads/bukti_pembayaran/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileName = uniqid() . "_" . basename($_FILES['bukti_pembayaran']['name']);
                $targetFile = $targetDir . $fileName;
                if (move_uploaded_file($_FILES['bukti_pembayaran']['tmp_name'], $targetFile)) {
                    $bukti = $fileName;
                }
            }

            if (empty($jenis_pembayaran) || !is_numeric($jumlah_bayar) || empty($bukti)) {
                $_SESSION['errorMsg'] = "Semua Form Harus Diisi!.";
                header('Location: index.php?page=pembayaran');
                exit;
            }


            // Insert pembayaran ke database
            $pembayaran = new Pembayaran();
            $insertBayar = $pembayaran->insertPembayaran([
                'id_sewa' => $id_sewa,
                'tanggal_pembayaran' => date('Y-m-d H:i:s'),
                'jumlah_bayar' => $jumlah_bayar,
                'metode_pembayaran' => $_POST['metode_pembayaran'],
                'bukti_pembayaran' => $bukti,
                'jenis_pembayaran' => $jenis_pembayaran,
                'tenggat_pembayaran' => $_POST['tenggat_pembayaran'] ?: null,
                'status_pembayaran' => 'Sedang Ditinjau',
                'tipe_pembayaran' => 'Sewa Baru',
            ]);

            if ($insertBayar) {
                Penyewa::updateStatusAkun($dataBooking['id_penyewa'], 'Menunggu Verifikasi');
                unset($_SESSION['booking']);
                header("Location: index.php?page=success&home");
                exit();
            } else {
                $_SESSION['errorMsg'] = "Gagal menyimpan data pembayaran.";
                header("Location: index.php?page=pembayaran");
                exit();
            }
        }

        // ✅ Ini dipanggil baik saat buka halaman maupun gagal input
        $harga_kamar = null;
        if (isset($_SESSION['booking'])) {
            $harga_kamar = Kamar::getHargaByNoKamar($_SESSION['booking']['no_kamar']);
        }

        include 'views/public/bayar.php';
    }

    public function detailKamar($tipe_kamar)
    {
        echo 'halo bang';
    }

    public function cekStatus()
    {
        $id_penyewa = $_SESSION['user_id'];
        $data = Penyewa::getProfilLengkap($id_penyewa);
        if ($data && $data['status_akun'] === 'Terverifikasi') {
            header("Location: index.php?page=penyewa_dashboard");
            exit;
        }

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
        include 'views/public/cek_status.php';
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
            header("Location: index.php?page=cek_status#edit-nama");
            exit;
        }
        header('Location: index.php?page=cek_status');
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
            header("Location: index.php?page=cek_status#edit-email");
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
            header("Location: index.php?page=cek_status#edit-email");
            exit;
        }

        header("Location: index.php?page=cek_status#verifikasi-email");
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
            header("Location: index.php?page=cek_status#verifikasi-email");
            exit;
        }

        if (!$otpData || time() > $otpData['expiry']) {
            $_SESSION['errorMsg'] = "OTP tidak valid atau kadaluarsa.";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=cek_status#verifikasi-email");
            exit;
        }

        if ($otp_input !== $otpData['otp']) {
            $_SESSION['errorMsg'] = "OTP tidak valid!";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=cek_status#verifikasi-email");
            exit;
        }

        penyewa::updateEmail($id_penyewa, $otpData['email_baru']);

        unset($_SESSION['otp_email_change']);
        $_SESSION['successMsg'] = "Email berhasil diperbarui.";
        header("Location: index.php?page=cek_status");
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
            header("Location: index.php?page=cek_status#edit-telp");
            exit;
        }
        header('Location: index.php?page=cek_status');
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
            header("Location: index.php?page=cek_status#edit-pass");
            exit;
        } elseif ($password_baru !== $konfirmasi) {
            $_SESSION['errorMsg'] = "Konfirmasi password tidak cocok.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=cek_status#edit-pass");
            exit;
        } elseif (strlen($password_baru) < 6) {
            $_SESSION['errorMsg'] = "Password baru minimal 6 karakter.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=cek_status#edit-pass");
            exit;
        } else {
            penyewa::updatePassword($id_penyewa, $password_baru);
            $_SESSION['successMsg'] = "Password berhasil diperbarui.";
        }

        header('Location: index.php?page=cek_status');
        exit;
    }

    public function default()
    {
        include 'views/public/notfound.php';
    }






}
