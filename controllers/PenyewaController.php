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
        $no_kamar = $_SESSION['no_kamar'];
        $feedbackList = Feedback::getFeedbackByNoKamar($no_kamar);
        include 'views/penyewa/keluhan.php';
    }

}
?>