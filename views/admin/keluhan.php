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
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($feedbackList as $feedback): ?>
                        <tr>
                            <td><?= number_format($feedback['id_feedback']) ?></td>
                            <td><?= number_format($feedback['no_kamar']) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($feedback['tanggal_feedback'])) ?></td>
                            <td><?= htmlspecialchars($feedback['isi_feedback']) ?></td>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
