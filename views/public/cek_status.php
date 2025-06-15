<?php $title = 'Profile Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin.php'; ?>

<?php include 'views/templates/navbar.php'; ?>
<div class="dashboard-container">
    <!-- Sidebar -->


    <!-- Main content area -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Selamat Datang, <span class="nama-penyewa"> <?php echo $data['nama_penyewa']; ?> !</span></h1>
        </div>

        <div class="profile-wrapper">
            <h2 class="section-title">Informasi Akun</h2>
            <?php if (isset($_SESSION['successMsg'])): ?>
                <div class="alert success"><?= htmlspecialchars($_SESSION['successMsg']) ?></div>
                <?php unset($_SESSION['successMsg']); ?>
            <?php endif; ?>

            <div class="card profile-card">
                <div class="profile-row">
                    <span>Nama</span>
                    <span>
                        <?= $data['nama_penyewa']; ?>
                        <button onclick="showEditModal('modalEditNama')" class="edit-btn">Edit</button>
                    </span>
                </div>
                <div class="profile-row">
                    <span>Email</span>
                    <span>
                        <?= $data['email_penyewa']; ?>
                        <button onclick="showEditModal('modalEditEmail')" class="edit-btn">Edit</button>
                    </span>
                </div>
                <div class="profile-row">
                    <span>No. Telepon</span>
                    <span>
                        <?= $data['no_telp_penyewa']; ?>
                        <button onclick="showEditModal('modalEditTelp')" class="edit-btn">Edit</button>
                    </span>
                </div>
                <div class="profile-row">
                    <span>Password</span>
                    <span>
                        ***********
                        <button onclick="showEditModal('modalEditPass')" class="edit-btn">Edit</button>
                    </span>
                </div>
                <div class="profile-row">
                    <span>Status Akun</span>
                    <span class="status-<?=
                        $data['status_akun'] == 'Terverifikasi' ? 'terverifikasi' : (
                            $data['status_akun'] == 'Menunggu Verifikasi' ? 'menunggu_verifikasi' : (
                                $data['status_akun'] == 'Umum' ? 'umum' : 'status_lain'
                            ))
                        ?>">
                        <?= $data['status_akun']; ?>
                    </span>
                </div>
            </div>

            <h2 class="section-title">Informasi Sewa Kos</h2>
            <?php if ($data['status_sewa']): ?>
                <div class="card profile-card">
                    <div class="profile-row"><span>Tipe Kamar</span><span><?= $data['tipe_kamar']; ?></span></div>
                    <div class="profile-row"><span>Nomor Kamar</span><span><?= $data['no_kamar']; ?></span></div>
                    <div class="profile-row"><span>Tanggal Mulai</span><span>
                            <?= date('d M Y', strtotime($data['tanggal_mulai'])); ?></span></div>
                    <div class="profile-row"><span>Tanggal Selesai</span><span>
                            <?= date('d M Y', strtotime($data['tanggal_selesai'])); ?></span></div>
                    <div class="profile-row">
                        <span>Sisa Hari Sewa</span>
                        <span class="sisa-hari">
                            <?= empty($data['sisa_hari_jam']) ? 'Tidak tersedia' : $data['sisa_hari_jam']; ?>
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <div class="card profile-card empty">Belum ada data sewa aktif.</div>
            <?php endif; ?>

            <h2 class="section-title">Informasi Pembayaran Terakhir</h2>
            <?php if ($data['status_sewa']): ?>
                <div class="card profile-card">
                    <div class="profile-row"><span>Tanggal Pembayaran</span><span>
                            <?= date('d M Y', strtotime($data['tanggal_pembayaran'])); ?></span></div>
                    <div class="profile-row"><span>Jumlah Bayar</span><span><?= $data['jumlah_bayar']; ?></span></div>
                    <div class="profile-row">
                        <span>Status Pembayaran</span>
                        <span class="status-<?=
                            $data['status_pembayaran'] == 'Terverifikasi' ? 'terverifikasi' : (
                                $data['status_pembayaran'] == 'Sedang Ditinjau' ? 'sedang_ditinjau' : (
                                    $data['status_pembayaran'] == 'Ditolak' ? 'ditolak' : 'status_lain'
                                ))
                            ?>">
                            <?= $data['status_pembayaran']; ?>
                        </span>
                    </div>
                </div>
            <?php else: ?>
                <div class="card profile-card empty">Belum ada data pembayaran.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Edit Email -->
<div id="modalEditEmail" class="modal hidden">
    <div class="modal-content card">
        <h3>Ubah Email</h3>
        <?php if (isset($_SESSION['errorMsg']) && ($_SESSION['errorModal'] ?? '') === 'EditEmail'): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['errorMsg']) ?></div>
            <?php
            unset($_SESSION['errorMsg'], $_SESSION['errorModal']);
            ?>
        <?php endif; ?>
        <form id="formEditEmail" action="index.php?page=public_ubah_email" method="POST">
            <div class="form-group">
                <label for="email_baru">Email Baru</label>
                <input type="email" id="email_baru" name="email_baru" required>
            </div>
            <div class="form-group">
                <label for="password_konfirmasi">Password Saat Ini</label>
                <input type="password" id="password_konfirmasi" name="password_konfirmasi" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Kirim OTP</button>
                <button type="button" class="btn-secondary" onclick="hideModal('modalEditEmail')">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi OTP -->
<div id="modalVerifikasiOTP" class="modal hidden">
    <div class="modal-content card">
        <h3>Verifikasi OTP</h3>
        <?php if (isset($_SESSION['errorMsg']) && ($_SESSION['errorModal'] ?? '') === 'VerifikasiOTP'): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['errorMsg']) ?></div>
            <?php
            unset($_SESSION['errorMsg'], $_SESSION['errorModal']);
            ?>
        <?php endif; ?>
        <form action="index.php?page=verifikasi_email_baru" method="POST">
            <div class="form-group">
                <label for="otp_kode">Masukkan Kode OTP</label>
                <input type="text" id="otp_kode" name="otp_kode" required maxlength="6" pattern="\d{6}"
                    title="Masukkan 6 digit angka OTP" autofocus>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Verifikasi</button>
                <button type="button" class="btn-secondary" onclick="hideModal('modalVerifikasiOTP')">Batal</button>
            </div>
        </form>
    </div>
</div>



<div id="modalEditNama" class="modal hidden">
    <div class="modal-content card">
        <h3>Ubah Nama</h3>
        <?php if (isset($_SESSION['errorModal']) && $_SESSION['errorModal'] === 'EditNama'): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['errorMsg'] ?? '') ?></div>
            <?php unset($_SESSION['errorMsg'], $_SESSION['errorModal']); ?>
        <?php endif; ?>
        <form action="index.php?page=public_ubah_nama" method="POST">
            <div class="form-group">
                <label for="nama_baru">Nama Baru</label>
                <input type="text" id="nama_baru" name="nama_baru" required>
            </div>
            <div class="form-group">
                <label for="password_konfirmasi">Password Saat Ini</label>
                <input type="password" id="password_konfirmasi" name="password_konfirmasi" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary" onclick="hideModal('modalEditNama')">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditTelp" class="modal hidden">
    <div class="modal-content card">
        <h3>Ubah No. Telepon</h3>
        <?php if (isset($_SESSION['errorModal']) && $_SESSION['errorModal'] === 'EditTelp'): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['errorMsg'] ?? '') ?></div>
            <?php unset($_SESSION['errorMsg'], $_SESSION['errorModal']); ?>
        <?php endif; ?>
        <form action="index.php?page=public_ubah_telp" method="POST">
            <div class="form-group">
                <label for="telp_baru">No. Telepon Baru</label>
                <input type="text" id="telp_baru" name="telp_baru" required>
            </div>
            <div class="form-group">
                <label for="password_konfirmasi">Password Saat Ini</label>
                <input type="password" id="password_konfirmasi" name="password_konfirmasi" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary" onclick="hideModal('modalEditTelp')">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditPass" class="modal hidden">
    <div class="modal-content card">
        <h3>Ubah Password</h3>
        <?php if (isset($_SESSION['errorModal']) && $_SESSION['errorModal'] === 'EditPass'): ?>
            <div class="alert error"><?= htmlspecialchars($_SESSION['errorMsg'] ?? '') ?></div>
            <?php unset($_SESSION['errorMsg'], $_SESSION['errorModal']); ?>
        <?php endif; ?>
        <form action="index.php?page=public_ubah_password" method="POST">
            <div class="form-group">
                <label for="password_lama">Password Lama</label>
                <input type="password" name="password_lama" required>
            </div>
            <div class="form-group">
                <label for="password_baru">Password Baru</label>
                <input type="password" name="password_baru" required>
            </div>
            <div class="form-group">
                <label for="konfirmasi_password">Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password" required>
            </div>
            <div class="modal-buttons">
                <button type="submit" class="btn-primary">Simpan</button>
                <button type="button" class="btn-secondary" onclick="hideModal('modalEditPass')">Batal</button>
            </div>
        </form>
    </div>
</div>




<style>
    .main-content {
        margin-left: 20px
    }

    .dashboard-container {
        display: flex;
        min-height: 100vh;
        margin-top: 70px;
    }

    .profile-row span:first-child {
        width: 300px;
        font-weight: 500;
    }

    .alert {
        padding: 10px 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        font-weight: 500;
    }

    .alert.success {
        background-color: #e6f4ea;
        color: #276738;
        border: 1px solid #b8dfc2;
    }

    .alert.error {
        background-color: #fcebea;
        color: #a94442;
        border: 1px solid #f5c6cb;
    }

    /* Modal backdrop */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .hidden {
        display: none;
    }

    /* Modal card-style content */
    .modal-content {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-content,
    .form-group,
    input {
        box-sizing: border-box;
    }

    /* Form layout */
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    /* Buttons */
    .modal-buttons {
        display: flex;
        justify-content: start;
        margin-top: 20px;
        gap: 10px;
    }

    .btn-primary {
        background-color: #2e8b57;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .btn-secondary {
        background-color: #ccc;
        color: black;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    /* Edit button inline dengan data */
    .edit-btn {
        background: none;
        color: #007BFF;
        border: none;
        font-size: 0.9rem;
        margin-left: 10px;
        cursor: pointer;
        text-decoration: underline;
        padding: 0;
    }
</style>

<?php
unset($_SESSION['errorModal']);
?>

<script>
    // Cek kalau ada hash #verifikasi-email di URL → buka modal OTP
    document.addEventListener("DOMContentLoaded", function () {
        const errorModal = '<?= $_SESSION['errorModal'] ?? '' ?>';
        const hash = window.location.hash;

        if (errorModal === 'EditNama' || window.location.hash === '#edit-nama') {
            showEditModal('modalEditNama');
        }

        if (errorModal === 'EditEmail' || window.location.hash === '#edit-email') {
            showEditModal('modalEditEmail');
        }

        if (errorModal === 'EditTelp' || window.location.hash === '#edit-telp') {
            showEditModal('modalEditTelp');
        }

        if (errorModal === 'VerifikasiOTP' || window.location.hash === '#verifikasi-email') {
            showEditModal('modalVerifikasiOTP');
        }

        if (errorModal === 'EditPass' || window.location.hash === '#edit-pass') {
            showEditModal('modalEditPass');
        }
    });

    function showEditModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }
    function hideModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
</script>

<?php require_once 'views/templates/footer.php'; ?>