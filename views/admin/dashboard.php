<?php require_once 'views/templates/header_admin.php'; ?>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h2 id="sidebar-title">Admin Panel</h2>
            <!-- Tombol Hamburger -->
            <button class="sidebar-toggle" id="sidebar-toggle">
                <i class="fas fa-bars"></i> 
            </button>
        </div>
        <ul class="sidebar-menu">
            <li><a href="index.php?page=admin_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="index.php?page=admin_daftar_kos"><i class="fas fa-home"></i> Daftar Kos</a></li>
            <li><a href="index.php?page=admin_data_penyewa"><i class="fas fa-users"></i> Data Penyewa</a></li>
            <li><a href="index.php?page=admin_keluhan"><i class="fas fa-exclamation-circle"></i> Keluhan Penyewa</a>
            </li>
            <li><a href="index.php?page=admin_verifikasi"><i class="fas fa-check-circle"></i> Verifikasi Penyewa</a>
            </li>
            <li><a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
        </ul>
    </div>
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