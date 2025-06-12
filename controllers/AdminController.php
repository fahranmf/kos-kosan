<?php
require_once 'middlewares/AuthMiddleware.php';
require_once 'models/Kamar.php';
require_once 'models/Penyewa.php';
require_once 'models/Feedback.php';
require_once 'models/Pembayaran.php';
require_once 'models/Sewa.php';

class AdminController
{
    // Menampilkan halaman Dashboard Admin
    public function dashboard()
    {
        AuthMiddleware::checkAdmin(); // Pastikan admin yang akses

        // Ambil data statistik
        $totalKos = Kamar::getTotalKos();
        $totalPenyewa = Penyewa::getTotalPenyewa();
        $totalKeluhan = Feedback::getTotalKeluhan();
        $tahunDipilih = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        $dataSewaPerBulan = Sewa::getJumlahSewaPerBulanByTahun($tahunDipilih);

        // Siapkan label dan data
        $labels = [];
        $jumlah = [];
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = DateTime::createFromFormat('!m', $i)->format('F');
            $jumlah[] = 0; // Default 0
        }

        foreach ($dataSewaPerBulan as $row) {
            $index = (int) $row['bulan'] - 1;
            $jumlah[$index] = $row['jumlah'];
        }


        $maxJumlah = !empty($jumlah) ? max($jumlah) : 0;
        $maxY = $maxJumlah + 3;

        // Load view dashboard
        require_once 'views/admin/dashboard.php';
    }

    // Menampilkan daftar kamar kos
    public function daftarKos()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;

        $totalData = Kamar::getTotalKos();
        $kamarList = Kamar::getAllKamar($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);

        require_once 'views/admin/daftar_kos.php';
    }

    // Menampilkan form edit kamar
    public function editKamar($id)
    {
        AuthMiddleware::checkAdmin();

        $kamar = Kamar::findById($id);
        if (!$kamar) {
            die("Kamar tidak ditemukan!");
        }

        require_once 'views/admin/edit_kamar.php';
    }

    // Proses update data kamar
    public function updateKamar()
    {
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

    //tambah kamar
    public function tambahKamar()
    {
        AuthMiddleware::checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validasi input dari form
            $tipe_kamar = $_POST['tipe_kamar'] ?? '';
            $harga_perbulan = $_POST['harga_perbulan'] ?? 0;
            $status = $_POST['status'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $fasilitas = $_POST['fasilitas'] ?? '';

            // Cek apakah file foto_kos diupload
            if (isset($_FILES['foto_kos']) && $_FILES['foto_kos']['error'] === UPLOAD_ERR_OK) {
                // Proses upload foto
                $targetDir = "uploads/foto_kos/";
                $fileName = basename($_FILES['foto_kos']['name']);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES['foto_kos']['tmp_name'], $targetFile)) {
                    // Foto berhasil di-upload
                    // Masukkan data kamar ke database
                    Kamar::insertKamar($tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas, $fileName);

                    // Redirect setelah berhasil
                    header('Location: index.php?page=admin_daftar_kos');
                    exit();
                } else {
                    echo "Gagal meng-upload foto kamar.";
                    exit();
                }
            } else {
                echo "Tidak ada file yang di-upload atau terjadi kesalahan pada file upload.";
                exit();
            }
        }
        include 'views/admin/tambah_kamar.php';
    }

    // Proses hapus kamar
    public function hapusKamar($id)
    {
        AuthMiddleware::checkAdmin();
        Kamar::deleteById($id);
        header('Location: index.php?page=admin_daftar_kos');
        exit();
    }

    public function keluhan()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 7;
        $offset = ($halamanAktif - 1) * $limit;

        $totalData = Feedback::getTotalKeluhan();
        $feedbackList = Feedback::getAllKeluhan($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/admin/keluhan.php';
    }
    public function dataPembayaran()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;

        $totalData = Pembayaran::getTotalPembayaran();
        $pembayaranList = Pembayaran::getAllPembayaran($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/admin/data_pembayaran.php';
    }
    public function dataPenyewa()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 7;
        $offset = ($halamanAktif - 1) * $limit;

        $totalData = Penyewa::getTotalPenyewa();
        $penyewaList = Penyewa::getAllPenyewa($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/admin/data_penyewa.php';
    }
    public function dataStatusSewa()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 7;
        $offset = ($halamanAktif - 1) * $limit;

        $totalData = Sewa::getTotalSewa();
        $statusSewaList = Sewa::getAllStatusSewa($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/admin/status_sewa.php';
    }

    public function verifikasi()
    {
        AuthMiddleware::checkAdmin();
        $halamanAktif = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
        $limit = 3;
        $offset = ($halamanAktif - 1) * $limit;
        $totalData = Penyewa::getTotalVerifPenyewa();
        $verifList = Penyewa::getAllAkun($limit, $offset);
        $totalHalaman = ceil($totalData / $limit);
        require_once 'views/admin/verifikasi_akun.php';
    }

    public function updateStatus()
    {
        AuthMiddleware::checkAdmin();
        $id = $_POST['id_feedback'];
        $status = $_POST['status_feedback'];
        Feedback::editStatus($id, $status);
        header('Location: index.php?page=admin_keluhan');
        exit;
    }

public function editStatusAkun()
{
    AuthMiddleware::checkAdmin();

    $id_pembayaran = $_POST['id_pembayaran'] ?? null;
    $statusPembayaran = $_POST['status_pembayaran'] ?? null;
    $id_penyewa = $_POST['id_penyewa'];
    
    if ($id_penyewa && $statusPembayaran && $id_pembayaran) {
        Penyewa::updateStatusPembayaranDanAkun($id_pembayaran, $statusPembayaran, $id_penyewa);
    }

    header('Location: index.php?page=admin_verifikasi');
    exit;
}

    public function lihatProfile()
    {
        AuthMiddleware::checkAdmin();
        $id_admin = $_SESSION['user_id'];
        $data = Admin::getProfilAdmin($id_admin);
        require_once 'views/admin/profie_admin.php';
    }

    public function ubahNama()
    {
        $id_admin = $_SESSION['user_id'];
        $nama_baru = $_POST['nama_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $Password = Admin::getPassword($id_admin);

        if ($password_konfirmasi == $Password) {
            Admin::updateNama($id_admin, $nama_baru);
            $_SESSION['successMsg'] = "Nama berhasil diperbarui.";
        } else {
            $_SESSION['errorMsg'] = "Password salah. Gagal memperbarui nama.";
            $_SESSION['errorModal'] = "EditNama";
            header("Location: index.php?page=admin_profile#edit-nama");
            exit;
        }
        header('Location: index.php?page=admin_profile');
        exit;
    }

    public function ubahEmail()
    {
        $id_admin = $_SESSION['user_id'];
        $email_baru = $_POST['email_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $password_asli = Admin::getPassword($id_admin);

        if ($password_konfirmasi !== $password_asli) {
            $_SESSION['errorMsg'] = "Password salah.";
            $_SESSION['errorModal'] = "EditEmail";
            header("Location: index.php?page=admin_profile#edit-email");
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
            header("Location: index.php?page=admin_profile#edit-email");
            exit;
        }

        header("Location: index.php?page=admin_profile#verifikasi-email");
        exit;
    }

    public function verifikasiEmailBaru()
    {
        $id_admin = $_SESSION['user_id'];
        $otp_input = trim($_POST['otp_kode'] ?? '');

        $otpData = $_SESSION['otp_email_change'] ?? null;

        if (empty($otp_input)) {
            $_SESSION['errorMsg'] = "OTP harus diisi.";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=admin_profile#verifikasi-email");
            exit;
        }

        if (!$otpData || time() > $otpData['expiry']) {
            $_SESSION['errorMsg'] = "OTP tidak valid atau kadaluarsa.";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=admin_profile#verifikasi-email");
            exit;
        }

        if ($otp_input !== $otpData['otp']) {
            $_SESSION['errorMsg'] = "OTP tidak valid!";
            $_SESSION['errorModal'] = "VerifikasiOTP";
            header("Location: index.php?page=admin_profile#verifikasi-email");
            exit;
        }

        Admin::updateEmail($id_admin, $otpData['email_baru']);

        unset($_SESSION['otp_email_change']);
        $_SESSION['successMsg'] = "Email berhasil diperbarui.";
        header("Location: index.php?page=admin_profile");
        exit;
    }



    public function ubahTelp()
    {
        $id_admin = $_SESSION['user_id'];
        $telp_baru = $_POST['telp_baru'];
        $password_konfirmasi = $_POST['password_konfirmasi'];

        $Password = Admin::getPassword($id_admin);

        if ($password_konfirmasi == $Password) {
            Admin::updateTelp($id_admin, $telp_baru);
            $_SESSION['successMsg'] = "Nomor Telpon berhasil diperbarui.";
        } else {
            $_SESSION['errorMsg'] = "Password salah. Gagal memperbarui no telpon.";
            $_SESSION['errorModal'] = "EditTelp";
            header("Location: index.php?page=admin_profile#edit-telp");
            exit;
        }
        header('Location: index.php?page=admin_profile');
        exit;
    }

    public function ubahPassword()
    {
        $id_admin = $_SESSION['user_id'];
        $password_lama = $_POST['password_lama'];
        $password_baru = $_POST['password_baru'];
        $konfirmasi = $_POST['konfirmasi_password'];

        $Password = Admin::getPassword($id_admin);

        if ($password_lama !== $Password) {
            $_SESSION['errorMsg'] = "Password lama salah.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=admin_profile#edit-pass");
            exit;
        } elseif ($password_baru !== $konfirmasi) {
            $_SESSION['errorMsg'] = "Konfirmasi password tidak cocok.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=admin_profile#edit-pass");
            exit;
        } elseif (strlen($password_baru) < 6) {
            $_SESSION['errorMsg'] = "Password baru minimal 6 karakter.";
            $_SESSION['errorModal'] = "EditPass";
            header("Location: index.php?page=admin_profile#edit-pass");
            exit;
        } else {
            Admin::updatePassword($id_admin, $password_baru);
            $_SESSION['successMsg'] = "Password berhasil diperbarui.";
        }

        header('Location: index.php?page=admin_profile');
        exit;
    }


}
?>