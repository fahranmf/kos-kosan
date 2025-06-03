<?php $title = 'Daftar Kos - Kos Putra Agan'; ?>
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
                            <th>Nomor Kamar</th>
                            <th>Foto Kamar</th>
                            <th>Tipe Kamar</th>
                            <th>Harga Perbulan</th>
                            <th>Deskripsi</th>
                            <th>Fasilitas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($kamarList as $kamar): ?>
                            <tr>
                                <td class="kolom"><?= number_format($kamar['no_kamar']) ?></td>
                                <td class="kolom"><img src="uploads/foto_kos/<?= htmlspecialchars($kamar['foto_kos']) ?>"
                                        alt="Bukti Pembayaran" class="foto-kamar" style="cursor:pointer"
                                        onclick="showModal(this.src)"></td>
                                <td class="kolom"><?= htmlspecialchars($kamar['tipe_kamar']) ?></td>
                                <td class="kolom">Rp <?= number_format($kamar['harga_perbulan'], 0, ',', '.') ?></td>
                                <td class="kolom"><?= htmlspecialchars($kamar['deskripsi']) ?></td>
                                <td class="kolom"><?= htmlspecialchars($kamar['fasilitas']) ?></td>
                                <td class="<?= $kamar['status'] == 'Kosong' ? 'kosong' : 'isi' ?>">
                                    <?= htmlspecialchars($kamar['status']) ?>
                                </td>
                                <td class="aksi">
                                    <div class="action-buttons">
                                        <!-- Tombol Edit dengan Icon Font Awesome -->
                                        <a href="index.php?page=edit_kamar&id=<?= $kamar['no_kamar'] ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> <!-- Icon Edit -->
                                        </a>

                                        <!-- Tombol Hapus dengan Icon Font Awesome -->
                                        <a href="index.php?page=hapus_kamar&id=<?= $kamar['no_kamar'] ?>" class="btn-hapus"
                                            onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                            <i class="fas fa-trash"></i> <!-- Icon Hapus -->
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($kamarList);
                        for ($i = 0; $i < $jumlahBarisKosong; $i++): ?>
                            <tr>
                                <td colspan="6" style="height: 72px;"></td>
                            </tr>
                        <?php endfor; ?>

                    </tbody>
                </table>
                <div class="tambah-kamar">
                    <a href="index.php?page=tambah_kamar" class="btn-tambah">Tambah Kamar</a>
                </div>

                <?php
                $startPage = max(1, $halamanAktif - 1);
                $endPage = min($totalHalaman, $startPage + 2);

                // Kalau di akhir, geser supaya tetap 3 halaman muncul
                if ($endPage - $startPage < 2) {
                    $startPage = max(1, $endPage - 2);
                }
                ?>

                <div class="pagination">
                    <a href="index.php?page=admin_daftar_kos&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_daftar_kos&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_daftar_kos&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_daftar_kos&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_daftar_kos&hal=<?= $totalHalaman ?>">&raquo;</a>
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