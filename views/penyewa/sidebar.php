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
        <li><a href="index.php?page=penyewa_dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="index.php?page=penyewa_keluhan"><i class="fas fa-exclamation-circle"></i> Ajukan Keluhan</a></li>
        <li><a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
    </ul>
</div>
