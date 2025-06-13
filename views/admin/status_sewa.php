<?php $title = 'Status Sewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin_daftarkos.php'; ?>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area  -->
    <div class="main-content">
        <div class="kamar-container">
            <div class="table-wrapper">

                <table class="kamar-table">
                    <thead>
                        <tr>
                            <th>Id Sewa</th>
                            <th>Id Penyewa</th>
                            <th>No. Kamar</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Tanggal Selesai Lama</th>
                            <th>Status Sewa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($statusSewaList as $sewa): ?>
                            <tr>
                                <td><?= number_format($sewa['id_sewa']) ?></td>
                                <td><?= number_format($sewa['id_penyewa']) ?></td>
                                <td><?= number_format($sewa['no_kamar']) ?></td>
                                <td><?= htmlspecialchars($sewa['tanggal_mulai']) ?></td>
                                <td><?= htmlspecialchars($sewa['tanggal_selesai']) ?></td>
                                <td><?= htmlspecialchars($sewa['tanggal_selesai_lama']  ?? '-') ?></td>
                                <td><?= htmlspecialchars($sewa['status_sewa']) ?></td>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($statusSewaList);
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
                    <a href="index.php?page=admin_status_sewa&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_status_sewa&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_status_sewa&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_status_sewa&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_status_sewa&hal=<?= $totalHalaman ?>">&raquo;</a>
                </div>
            </div>
        </div>