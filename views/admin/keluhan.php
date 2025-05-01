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
                </tbody>
            </table>
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