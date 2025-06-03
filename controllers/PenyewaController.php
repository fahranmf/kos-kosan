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
        $id_penyewa = $_SESSION['user_id'];
        $pembayaranList = Penyewa::getDataPembayaranPenyewaById($id_penyewa);
        require_once 'views/penyewa/histori_pembayaran.php';
    }

}
?>