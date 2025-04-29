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
        <li><a href="index.php?page=admin_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="index.php?page=admin_daftar_kos"><i class="fas fa-home"></i> Daftar Kos</a></li>
        <li><a href="index.php?page=admin_data_penyewa"><i class="fas fa-users"></i> Data Penyewa</a></li>
        <li><a href="index.php?page=admin_keluhan"><i class="fas fa-exclamation-circle"></i> Keluhan Penyewa</a></li>
        <li><a href="index.php?page=admin_verifikasi"><i class="fas fa-check-circle"></i> Verifikasi Penyewa</a></li>
        <li><a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
    </ul>
</div>
