<?php require_once 'views/templates/header_sidebar.php'; ?>

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
        <li><a href="index.php?page=admin_dashboard"><i class="fas fa-tachometer-alt"></i> <span class="menu-text">Dashboard</span></a></li>
        <li><a href="index.php?page=admin_daftar_kos"><i class="fas fa-home"></i> <span class="menu-text">Daftar Kos</span></a></li>
        <li><a href="index.php?page=admin_data_penyewa"><i class="fas fa-users"></i> <span class="menu-text">Data Penyewa</span></a></li>
        <li><a href="index.php?page=admin_status_sewa"><i class="fas fa-tasks"></i> <span class="menu-text">Status Sewa Kos</span></a></li>
        <li><a href="index.php?page=admin_data_pembayaran"><i class="fas fa-money-bill-wave"></i> <span class="menu-text">History Pembayaran</span></a></li>
        <li><a href="index.php?page=admin_keluhan"><i class="fas fa-exclamation-circle"></i> <span class="menu-text">Keluhan Penyewa</span></a></li>
        <li><a href="index.php?page=admin_verifikasi"><i class="fas fa-check-circle"></i> <span class="menu-text">Verifikasi Penyewa</span></a></li>
        <li><a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i> <span class="menu-text">Log Out</span></a></li>
    </ul>
</div>
