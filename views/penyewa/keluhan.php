<?php $title = 'Keluhan Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_penyewa.php'; ?>


<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area untuk Daftar Kos -->
    <div class="main-content">
        <div class="form-keluhan-container">
            <h2>Riwayat Keluhan Saya</h2>
            <?php if (empty($feedbackList)): ?>
                <p>Tidak ada keluhan yang pernah diajukan.</p>
            <?php else: ?>
                <div class="kamar-container">
                    <div class="table-wrapper">


                        <table class="kamar-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Isi Keluhan</th>
                                    <th>Status</th>
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
                                        <td><?= htmlspecialchars($feedback['tanggal_feedback']) ?></td>
                                        <td><?= htmlspecialchars($feedback['isi_feedback']) ?></td>
                                        <td class="<?= $class ?>"><?= htmlspecialchars($feedback['status_feedback']) ?></td>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php
                                $jumlahBarisKosong = $limit - count($feedbackList);
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
                            <a href="index.php?page=penyewa_keluhan&hal=1">&laquo;</a>

                            <?php if ($halamanAktif > 1): ?>
                                <a href="index.php?page=penyewa_keluhan&hal=<?= $halamanAktif - 1 ?>"> &lsaquo;</a>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="index.php?page=penyewa_keluhan&hal=<?= $i ?>"
                                    class="<?= $i == $halamanAktif ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($halamanAktif < $totalHalaman): ?>
                                <a href="index.php?page=penyewa_keluhan&hal=<?= $halamanAktif + 1 ?>">&rsaquo;</a>
                            <?php endif; ?>
                            <a href="index.php?page=penyewa_keluhan&hal=<?= $totalHalaman ?>">&raquo;</a>
                        </div>
                    <?php endif; ?>

                </div>

                <form action="index.php?page=penyewa_keluhan" method="POST">
                    <div class="form-keluhan-group">
                        <label for="isi_feedback">Ajukan Keluhan</label>
                        <textarea id="isi_feedback" name="isi_feedback" rows="5"
                            placeholder="Tulis keluhan Anda di sini..." required></textarea>
                    </div>
                    <div class="form-keluhan-group">
                        <button type="submit" class="btn-kirim">Kirim Keluhan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>