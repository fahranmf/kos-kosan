<?php $title = 'History Pembayaran - Kos Putra Agan'; ?>
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
                            <th>Id Pembayaran</th>
                            <th>Id Sewa</th>
                            <th>Tanggal Bayar</th>
                            <th>Jumlah</th>
                            <th>Metode Pembayaran</th>
                            <th>Bukti Pembayaran</th>
                            <th>Jenis Pembayaran</th>
                            <th>Tenggat Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pembayaranList as $pembayaran): ?>
                            <tr>
                                <td><?= number_format($pembayaran['id_pembayaran']) ?></td>
                                <td><?= number_format($pembayaran['id_sewa']) ?></td>
                                <td><?= htmlspecialchars($pembayaran['tanggal_pembayaran']) ?></td>
                                <td>Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($pembayaran['metode_pembayaran']) ?></td>
                                <td>
                                    <img src="uploads/bukti_pembayaran/<?= htmlspecialchars($pembayaran['bukti_pembayaran']) ?>"
                                        alt="Bukti Pembayaran" class="foto-kamar"
                                        style="width:100px; height:100px; cursor:pointer" onclick="showModal(this.src)">
                                </td>
                                <td class="<?= $pembayaran['jenis_pembayaran'] == 'Lunas' ? 'lunas' : 'cicil' ?>">
                                    <?= htmlspecialchars($pembayaran['jenis_pembayaran']) ?>
                                <td><?= htmlspecialchars($pembayaran['tenggat_pembayaran'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($pembayaranList);
                        for ($i = 0; $i < $jumlahBarisKosong; $i++): ?>
                            <tr>
                                <td colspan="6" style="height: 92px;"></td>
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
                    <a href="index.php?page=admin_data_pembayaran&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_data_pembayaran&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_data_pembayaran&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_data_pembayaran&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_data_pembayaran&hal=<?= $totalHalaman ?>">&raquo;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal buat tampilin gambar gede -->
<div id="myModal">
    <span onclick="closeModal()"
        style="position:absolute;top:20px;right:45px;color:white;font-size:40px;font-weight:bold;cursor:pointer">&times;</span>
    <img id="imgModal" style="margin:auto;display:block;max-width:90%;max-height:80%">
</div>