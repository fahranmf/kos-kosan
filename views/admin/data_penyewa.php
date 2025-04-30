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
                        <th>Id Penyewa</th>
                        <th>Nama</th>
                        <th>No Telp</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penyewaList as $penyewa): ?>
                        <tr>
                            <td><?= number_format($penyewa['id_penyewa']) ?></td>
                            <td><?= htmlspecialchars($penyewa['nama_penyewa']) ?></td>
                            <td><?= htmlspecialchars($penyewa['no_telp_penyewa']) ?></td>
                            <td><?= htmlspecialchars($penyewa['email_penyewa']) ?></td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
