<?php $title = 'Data Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin_daftarkos.php'; ?>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area untuk Daftar Kos -->
    <div class="main-content">
        <div class="kamar-container">
            <div class="table-wrapper">

                <table class="kamar-table">
                    <thead>
                        <tr>
                            <th>Id Penyewa</th>
                            <th>Nama</th>
                            <th>No Telp</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($penyewaList as $penyewa): ?>
                            <tr>
                                <td><?= number_format($penyewa['id_penyewa']) ?></td>
                                <td><?= htmlspecialchars($penyewa['nama_penyewa']) ?></td>
                                <td><?= htmlspecialchars($penyewa['no_telp_penyewa']) ?></td>
                                <td><?= htmlspecialchars($penyewa['email_penyewa']) ?></td>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($penyewaList);
                        for ($i = 0; $i < $jumlahBarisKosong; $i++): ?>
                            <tr>
                                <td colspan="6" style="height: 24px;"></td>
                            </tr>
                        <?php endfor; ?>

                    </tbody>
                </table>

                <?php
                $startPage = max(1, $halamanAktif - 1);
                $endPage = min($totalHalaman, $startPage + 2);

                // Kalau di akhir, geser supaya tetap 3 halaman muncul
                if ($endPage - $startPage < 2) {
                    $startPage = max(1, $endPage - 2);
                }
                ?>

                <div class="pagination">
                    <a href="index.php?page=admin_data_penyewa&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_data_penyewa&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_data_penyewa&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_data_penyewa&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_data_penyewa&hal=<?= $totalHalaman ?>">&raquo;</a>
                </div>
            </div>
        </div>