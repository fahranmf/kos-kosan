<?php require_once 'views/templates/header_admin.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <?php include('sidebar.php'); ?>
    <!-- Main content area -->
    <div class="main-content">
        <div class="dashboard-header">
            <h1>Dashboard Admin</h1>
        </div>

        <div class="stats-container">
            <div class="stat-card">
                <h3>Jumlah Kos</h3>
                <p><?php echo $totalKos; ?></p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Penyewa</h3>
                <p><?php echo $totalPenyewa; ?></p>
            </div>
            <div class="stat-card">
                <h3>Jumlah Keluhan</h3>
                <p><?php echo $totalKeluhan; ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>