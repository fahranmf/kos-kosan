<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Feedback.php';
require_once 'models/Penyewa.php';

class PenyewaController
{
    public function dashboard()
    {
        AuthMiddleware::checkPenyewa();
        require_once 'views/penyewa/dashboard.php';
    }

    public function keluhan()
    {
        AuthMiddleware::checkPenyewa();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $no_kamar = $_SESSION['no_kamar'];
            $isi_feedback = $_POST['isi_feedback'] ?? '';
            Feedback::kirimFeedback($no_kamar, $isi_feedback);
        }
        $no_kamar = $_SESSION['no_kamar'];
        $feedbackList = Feedback::getFeedbackByNoKamar($no_kamar);
        include 'views/penyewa/keluhan.php';
    }

}
?>