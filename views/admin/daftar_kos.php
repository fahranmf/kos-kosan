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
                        <th>Nomor Kamar</th>
                        <th>Foto Kamar</th>
                        <th>Tipe Kamar</th>
                        <th>Harga Perbulan</th>
                        <th>Deskripsi</th>
                        <th>Fasilitas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kamarList as $kamar): ?>
                        <tr>
                            <td><?= number_format($kamar['no_kamar']) ?></td>
                            <td><img src="uploads/foto_kos/<?= htmlspecialchars($kamar['foto_kos']) ?>" alt="Foto Kamar"
                                    class="foto-kamar"></td>
                            <td><?= htmlspecialchars($kamar['tipe_kamar']) ?></td>
                            <td>Rp <?= number_format($kamar['harga_perbulan'], 0, ',', '.') ?></td>
                            <td><?= htmlspecialchars($kamar['deskripsi']) ?></td>
                            <td><?= htmlspecialchars($kamar['fasilitas']) ?></td>
                            <td class="<?= $kamar['status'] == 'Kosong' ? 'kosong' : 'isi' ?>">
                                <?= htmlspecialchars($kamar['status']) ?>
                            </td>
                            <td class="aksi">
                                <div class="action-buttons">
                                    <!-- Tombol Edit dengan Icon Font Awesome -->
                                    <a href="index.php?page=edit_kamar&id=<?= $kamar['no_kamar'] ?>" class="btn-edit">
                                        <i class="fas fa-edit"></i> <!-- Icon Edit -->
                                    </a>
    
                                    <!-- Tombol Hapus dengan Icon Font Awesome -->
                                    <a href="index.php?page=hapus_kamar&id=<?= $kamar['no_kamar'] ?>" class="btn-hapus"
                                        onclick="return confirm('Yakin ingin menghapus kamar ini?')">
                                        <i class="fas fa-trash"></i> <!-- Icon Hapus -->
                                    </a>
                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="tambah-kamar">
            <a href="index.php?page=tambah_kamar" class="btn-tambah">Tambah Kamar</a>