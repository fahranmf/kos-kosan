<?php require_once 'views/templates/header_sidebar.php'; ?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2 id="sidebar-title">Penyewa</h2>
        <!-- Tombol Hamburger -->
        <button class="sidebar-toggle" id="sidebar-toggle">
            <i class="fas fa-bars"></i> 
        </button>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php?page=penyewa_dashboard"><i class="fas fa-user"></i> <span class="menu-text">Profile</span></a></li>
        <li><a href="index.php?page=penyewa_perpanjang_kos"><i class="fas fa-hourglass-end"></i><span class="menu-text">Perpanjang Kos?</span></a></li>
        <li><a href="index.php?page=penyewa_riwayat"><i class="fas fa-history"></i><span class="menu-text">Riwayat Pembayaran</span></a></li>
        <li><a href="index.php?page=penyewa_keluhan"><i class="fas fa-exclamation-circle"></i> <span class="menu-text">Ajukan Keluhan</span></a></li>
        <li><a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i><span class="menu-text">Log Out</span></a></li>
    </ul>
</div>
