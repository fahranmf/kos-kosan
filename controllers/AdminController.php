<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Kamar.php';
require_once 'models/Penyewa.php';
require_once 'models/Feedback.php';

class AdminController {
    // Menampilkan halaman Dashboard Admin
    public function dashboard() {
        AuthMiddleware::checkAdmin(); // Pastikan admin yang akses

        // Ambil data statistik
        $totalKos = Kamar::getTotalKos();
        $totalPenyewa = Penyewa::getTotalPenyewa();
        $totalKeluhan = Feedback::getTotalKeluhan();

        // Load view dashboard
        require_once 'views/admin/dashboard.php';
    }

    // Menampilkan daftar kamar kos
    public function daftarKos() {
        AuthMiddleware::checkAdmin();

        $kamarList = Kamar::getAllKamar();
        require_once 'views/admin/daftar_kos.php';
    }

    // Menampilkan form edit kamar
    public function editKamar($id) {
        AuthMiddleware::checkAdmin();

        $kamar = Kamar::findById($id);
        if (!$kamar) {
            die("Kamar tidak ditemukan!");
        }

        require_once 'views/admin/edit_kamar.php';
    }

    // Proses update data kamar
    public function updateKamar() {
        AuthMiddleware::checkAdmin();
    
        // Validasi input
        $id = $_POST['id'] ?? null;
        $foto_kos_lama = $_POST['foto_kos_lama'] ?? ''; // Simpan foto lama dari form hidden input
        $tipe_kamar = $_POST['tipe_kamar'] ?? '';
        $harga_perbulan = $_POST['harga_perbulan'] ?? 0;
        $status = $_POST['status'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $fasilitas = $_POST['fasilitas'] ?? '';
    
        if (!$id) {
            die("ID kamar tidak valid.");
        }
    
        $foto_kos_baru = $foto_kos_lama; // Defaultnya pake foto lama
    
        // Cek kalau ada upload gambar baru
        if (isset($_FILES['foto_kos']) && $_FILES['foto_kos']['error'] == 0) {
            $targetDir = "uploads/foto_kos/";
            $fileName = basename($_FILES['foto_kos']['name']);
            $targetFile = $targetDir . $fileName;
    
            if (move_uploaded_file($_FILES['foto_kos']['tmp_name'], $targetFile)) {
                // Kalau berhasil upload, hapus gambar lama (kalau ada)
                if (!empty($foto_kos_lama) && file_exists($targetDir . $foto_kos_lama)) {
                    unlink($targetDir . $foto_kos_lama);
                }
                $foto_kos_baru = $fileName; // Update pake nama foto baru
            } else {
                die("Gagal meng-upload gambar.");
            }
        }
    
        // Update data kamar
        Kamar::update($id, $foto_kos_baru, $tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas);
    
        // Redirect setelah update
        header('Location: index.php?page=admin_daftar_kos');
        exit();
    }
    
    

    // Proses hapus kamar
    public function hapusKamar($id) {
        AuthMiddleware::checkAdmin();

        Kamar::deleteById($id);

        // Redirect setelah hapus
        header('Location: index.php?page=daftar_kos');
        exit();
    }
}
?>
