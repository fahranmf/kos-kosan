<?php $title = 'Keluhan - Kos Putra Agan'; ?>
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
                            <th>No.</th>
                            <th>No Kamar</th>
                            <th>Tanggal</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $statusClass = [
                            'Belum Dibaca' => 'status-belum',
                            'Sudah Dibaca' => 'status-sudah',
                            'Sedang Diproses' => 'status-proses',
                            'Selesai Ditangani' => 'status-selesai',
                        ];
                        ?>
                        <?php foreach ($feedbackList as $feedback): ?>
                            <?php
                            $class = $statusClass[$feedback['status_feedback']] ?? '';
                            ?>
                            <tr>

                                <td><?= number_format($feedback['id_feedback']) ?></td>
                                <td><?= number_format($feedback['no_kamar']) ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($feedback['tanggal_feedback'])) ?></td>
                                <td><?= htmlspecialchars($feedback['isi_feedback']) ?></td>
                                <td class="<?= $class ?>"><?= htmlspecialchars($feedback['status_feedback']) ?></td>
                                </td>
                                <td class="aksi">
                                    <div class="action-buttons">
                                        <!-- Tombol Edit dengan Icon Font Awesome -->
                                        <a href="#" class="btn-edit"
                                            onclick="openEditModal(<?= $feedback['id_feedback'] ?>, '<?= $feedback['status_feedback'] ?>')">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                    </div>
                            </tr>
                        <?php endforeach; ?>

                        <?php
                        $jumlahBarisKosong = $limit - count($feedbackList);
                        for ($i = 0; $i < $jumlahBarisKosong; $i++): ?>
                            <tr>
                                <td colspan="6" style="height: 32px;"></td>
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
                    <a href="index.php?page=admin_keluhan&hal=1">&laquo;</a>

                    <?php if ($halamanAktif > 1): ?>
                        <a href="index.php?page=admin_keluhan&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <a href="index.php?page=admin_keluhan&hal=<?= $i ?>"
                            class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($halamanAktif < $totalHalaman): ?>
                        <a href="index.php?page=admin_keluhan&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                    <?php endif; ?>
                    <a href="index.php?page=admin_keluhan&hal=<?= $totalHalaman ?>">&raquo;</a>
                </div>
            </div>
        </div>


        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" onclick="closeEditModal()">&times;</span>
                <h3>Edit Status Feedback</h3>
                <form action="index.php?page=update_status_feedback" method="POST">
                    <input type="hidden" name="id_feedback" id="edit_id_feedback">

                    <label for="edit_status_feedback">Status</label>
                    <select name="status_feedback" id="edit_status_feedback" required>
                        <option value="Belum Dibaca">Belum Dibaca</option>
                        <option value="Sudah Dibaca">Sudah Dibaca</option>
                        <option value="Sedang Diproses">Sedang Diproses</option>
                        <option value="Selesai Ditangani">Selesai Ditangani</option>
                    </select>

                    <div class="modal-buttons">
                        <button type="submit" class="btn-save">Simpan</button>
                        <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    </div>
                </form>
            </div>
        </div>
