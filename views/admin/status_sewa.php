<?php $title = 'Status Sewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin_daftarkos.php'; ?>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area  -->
    <div class="main-content">
        <div class="kamar-container">
            <table class="kamar-table">
                <thead>
                    <tr>
                        <th>Id Sewa</th>
                        <th>Id Penyewa</th>
                        <th>No. Kamar</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Status Sewa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statusSewaList as $sewa): ?>
                        <tr>
                            <td><?= number_format($sewa['id_sewa']) ?></td>
                            <td><?= number_format($sewa['id_penyewa']) ?></td>
                            <td><?= number_format($sewa['no_kamar']) ?></td>
                            <td><?= htmlspecialchars($sewa['tanggal_mulai']) ?></td>
                            <td><?= htmlspecialchars($sewa['tanggal_selesai']) ?></td>
                            <td><?= htmlspecialchars($sewa['status_sewa']) ?></td>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
