<?php require_once 'views/templates/header_admin_daftarkos.php'; ?>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area untuk Daftar Kos -->
    <div class="main-content">
        <div class="kamar-container">
            <table class="kamar-table">
                <thead>
                    <tr>
                        <th>Id Pembayaran</th>
                        <th>Id Sewa</th>
                        <th>Tanggal Bayar</th>
                        <th>Jumlah</th>
                        <th>Metode Pembayaran</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status Pembayaran</th>
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
                                     alt="Bukti Pembayaran"
                                     class="foto-kamar"
                                     style="width:100px; height:100px; cursor:pointer"
                                     onclick="showModal(this.src)">
                            </td>
                            <td class="<?= $pembayaran['status_pembayaran'] == 'Lunas' ? 'lunas' : 'cicil' ?>">
                            <?= htmlspecialchars($pembayaran['status_pembayaran']) ?>
                            <td><?= htmlspecialchars($pembayaran['tenggat_pembayaran']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal buat tampilin gambar gede -->
<div id="myModal">
    <span onclick="closeModal()" style="position:absolute;top:20px;right:45px;color:white;font-size:40px;font-weight:bold;cursor:pointer">&times;</span>
    <img id="imgModal" style="margin:auto;display:block;max-width:90%;max-height:80%">
</div>

