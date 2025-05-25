<?php $title = 'Profile Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Selamat Datang, <span class="nama-penyewa"> <?php echo $data['nama_penyewa']; ?> !</span></h1>
        </div>

        <div class="profile-wrapper">
            <h2 class="section-title">Informasi Akun</h2>
            <div class="card profile-card">
                <div class="profile-row"><span>Nama</span><span><?= $data['nama_penyewa']; ?></span></div>
                <div class="profile-row"><span>Email</span><span><?= $data['email_penyewa']; ?></span></div>
                <div class="profile-row"><span>No. Telepon</span><span><?= $data['no_telp_penyewa']; ?></span></div>
                <div class="profile-row"><span>Status Akun</span><span class="status"><?= $data['status_akun']; ?></span></div>
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
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>