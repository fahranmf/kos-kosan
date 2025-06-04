<?php $title = 'Verifikasi Akun Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin_daftarkos.php'; ?>

<style>
    .main-content {
        width: auto;
    }
</style>

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
                            <th>Id Pembayaran</th>
                            <th>Email Penyewa</th>
                            <th>Jumlah Bayar</th>
                            <th>Bukti Pembayaran</th>
                            <th>Jenis Pembayaran</th>
                            <th>Status Akun</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verifList as $verif): ?>
                            <tr>
                                <td><?= number_format($verif['id_penyewa']) ?></td>
                                <td><?= number_format($verif['id_pembayaran']) ?></td>
                                <td><?= htmlspecialchars($verif['email_penyewa']) ?></td>
                                <td>Rp <?= number_format($verif['jumlah_bayar'], 0, ',', '.') ?></td>
                                <td>
                                    <img src="uploads/bukti_pembayaran/<?= htmlspecialchars($verif['bukti_pembayaran']) ?>"
                                        alt="Bukti Pembayaran" class="foto-kamar"
                                        style="width:100px; height:100px; cursor:pointer" onclick="showModal(this.src)">
                                </td>
                                <td class="<?= $verif['jenis_pembayaran'] == 'Lunas' ? 'lunas' : 'cicil' ?>">
                                    <?= htmlspecialchars($verif['jenis_pembayaran']) ?>
                                </td>
                                <td
                                    class="<?= $verif['status_akun'] == 'Terverifikasi' ? 'terverifikasi' : 'menunggu_verifikasi' ?>">
                                    <?= htmlspecialchars($verif['status_akun']) ?>
                                </td>
                                <td class="<?=
                                    $verif['status_pembayaran'] == 'Terverifikasi' ? 'terverifikasi' : (
                                        $verif['status_pembayaran'] == 'Sedang Ditinjau' ? 'sedang_ditinjau' : (
                                            $verif['status_pembayaran'] == 'Ditolak' ? 'ditolak' : 'status_lain'
                                        ))
                                    ?>">
                                    <?= htmlspecialchars($verif['status_pembayaran']) ?>
                                </td>
                                <td class="aksi">
                                    <div class="action-buttons">
                                        <!-- Tombol Edit dengan Icon Font Awesome -->
                                        <a href="#" class="btn-edit"
                                            onclick="openEditModal(<?= $verif['id_penyewa'] ?>, '<?= $verif['status_pembayaran'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($verifList);
                        for ($i = 0; $i < $jumlahBarisKosong; $i++): ?>
                            <tr>
                                <td colspan="6" style="height: 72px;"></td>
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
                    <a href="index.php?page=admin_verifikasi&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_verifikasi&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_verifikasi&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_verifikasi&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_verifikasi&hal=<?= $totalHalaman ?>">&raquo;</a>
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


<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditModal()">&times;</span>
        <h3>Edit Status Pembayaran</h3>
        <form action="index.php?page=admin_update_status_akun" method="POST">
            <input type="hidden" name="id_penyewa" id="edit_id_feedback">

            <label for="edit_status_feedback">Status</label>
            <select name="status_pembayaran" id="edit_status_feedback" required>
                <option value="Terverifikasi">Terverifikasi</option>
                <option value="Sedang Ditinjau">Sedang Ditinjau</option>
                <option value="Ditolak">Ditolak</option>
            </select>

            <div class="modal-buttons">
                <button type="submit" class="btn-save">Simpan</button>
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
            </div>
        </form>
    </div>
</div>