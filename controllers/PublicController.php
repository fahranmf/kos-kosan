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
                'status_pembayaran' => 'Sedang Ditinjau'
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
        echo'halo bang';
    }
    public function default()
    {
        include 'views/public/notfound.php';
    }






}
