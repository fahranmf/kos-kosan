<?php $title = 'Riwayat Pembayaran - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_penyewa.php'; ?>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>


    <!-- Main content area untuk Daftar Kos -->
    <div class="main-content">
        <div class="kamar-container">
            <table class="kamar-table">
                <thead>
                    <tr>
                        <th>Tanggal Bayar</th>
                        <th>No. Kamar</th>
                        <th>Jumlah Bayar</th>
                        <th>Jenis Pembayaran</th>
                        <th>Tenggat Pembayaran</th>
                        <th>Bukti Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pembayaranList as $pembayaran): ?>
                        <tr>
                            <td><?= htmlspecialchars($pembayaran['tanggal_pembayaran']) ?></td>
                            <td class="kolom"><?= number_format($pembayaran['no_kamar']) ?></td>
                            <td>Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                            <td class="<?= $pembayaran['jenis_pembayaran'] == 'Lunas' ? 'lunas' : 'cicil' ?>">
                                <?= htmlspecialchars($pembayaran['jenis_pembayaran']) ?>
                            <td><?= htmlspecialchars($pembayaran['tenggat_pembayaran'] ?? '-') ?></td>
                            <td>
                                <img src="uploads/bukti_pembayaran/<?= htmlspecialchars($pembayaran['bukti_pembayaran']) ?>"
                                    alt="Bukti Pembayaran" class="foto-kamar"
                                    style="width:100px; height:100px; cursor:pointer" onclick="showModal(this.src)">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal buat tampilin gambar gede -->
<div id="myModal">
    <span onclick="closeModal()"
        style="position:absolute;top:20px;right:45px;color:white;font-size:40px;font-weight:bold;cursor:pointer">&times;</span>
    <img id="imgModal" style="margin:auto;display:block;max-width:90%;max-height:80%">
</div>