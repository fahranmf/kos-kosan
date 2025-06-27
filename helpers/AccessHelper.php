<?php
require_once 'models/Kamar.php';
require_once 'models/Penyewa.php';
require_once 'models/Feedback.php';
require_once 'models/Pembayaran.php';
require_once 'models/Sewa.php';
require_once 'vendor/autoload.php';

class AccessHelper
{
    public static function blokirJikaCicilBelumLunas($id_penyewa)
    {
        $id_penyewa = $_SESSION['user_id'];
        $sewa = Sewa::getSewaAktifByPenyewa($id_penyewa);
        if (!is_array($sewa)) {
            return; // kalau gak ada sewa aktif, gak perlu blokir
        }

        $pembayaranTerakhir = Pembayaran::getTerakhirBySewa($sewa['id_sewa']);
        if (
            $pembayaranTerakhir &&
            strtolower($pembayaranTerakhir['jenis_pembayaran']) === 'cicil'
        ) {
            $_SESSION['errorMsg'] = 'Akses ditolak karena cicilan belum lunas.';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }
    }

    public static function sedangCicilBelumLunas($id_penyewa)
    {
        $id_penyewa = $_SESSION['user_id'];
        $sewa = Sewa::getSewaAktifByPenyewa($id_penyewa);
        if (!is_array($sewa))
            return false;

        $pembayaranTerakhir = Pembayaran::getTerakhirBySewa($sewa['id_sewa']);
        return (
            $pembayaranTerakhir &&
            strtolower($pembayaranTerakhir['jenis_pembayaran']) === 'cicil');
    }

    public static function blokirJikaBukanCicilanAktif($id_penyewa)
    {
        $sewa = Sewa::getSewaAktifByPenyewa($id_penyewa);
        if (!is_array($sewa)) {
            $_SESSION['errorMsg'] = 'Akses pelunasan tidak valid.';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }

        $pembayaranTerakhir = Pembayaran::getTerakhirBySewa($sewa['id_sewa']);
        if (
            !$pembayaranTerakhir ||
            strtolower($pembayaranTerakhir['jenis_pembayaran']) !== 'cicil' ||
            strtolower($pembayaranTerakhir['status_pembayaran']) === 'lunas'
        ) {
            $_SESSION['errorMsg'] = 'Akses pelunasan tidak diperbolehkan.';
            header('Location: index.php?page=penyewa_dashboard');
            exit;
        }
    }

}
?>