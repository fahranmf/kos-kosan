<?php $title = 'Detail Kamar - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin.php'; ?>
<?php include 'views/templates/navbar.php'; ?>

<link rel="stylesheet" href="assets/css/detail_kos.css">

<div class="wrapper">
    <div class="container detail-kamar">
        <h2>Detail Kamar Tipe <?= htmlspecialchars($kamar['tipe_kamar']) ?></h2>

        <h3>Foto Kamar</h3>
        <div class="galeri-scroll">
            <?php
            $semua_foto = [];

            if (!empty($kamar['foto_kos'])) {
                $semua_foto[] = 'uploads/foto_kos/' . $kamar['foto_kos'];
            }

            foreach ($foto_detail as $foto) {
                $semua_foto[] = 'uploads/foto_detail_kamar/' . $foto['nama_file'];
            }

            foreach ($semua_foto as $src): ?>
                <div class="foto-item" onclick="previewFoto('<?= htmlspecialchars($src) ?>')">
                    <img src="<?= htmlspecialchars($src) ?>" alt="Foto Kamar">
                </div>
            <?php endforeach; ?>
        </div>

        <div class="info">
            <p><strong>Deskripsi:</strong> <?= htmlspecialchars($kamar['deskripsi']) ?></p>
            <p><strong>Fasilitas:</strong> <?= htmlspecialchars($kamar['fasilitas']) ?></p>
        </div>

        <div class="booking">
            <p class="harga">Rp <?= number_format($kamar['harga_perbulan'], 0, ',', '.') ?> / bulan</p>
            <a class="btn-booking" href="index.php?page=home#rooms">
                Booking Sekarang
            </a>
        </div>
    </div>

    <div class="container sidebar-kosong">
        <h3>Kamar Kosong Tersedia</h3>
        <ul class="kamar-kosong-list">
            <?php if (!empty($kamar_kosong)): ?>
                <?php foreach ($kamar_kosong as $kosong): ?>
                    <li>No. Kamar <?= htmlspecialchars($kosong['no_kamar']) ?></li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>-</li>
            <?php endif; ?>

        </ul>
    </div>
</div>


<!-- Modal Preview -->
<div id="modal-preview" class="modal-preview" onclick="handleBackdropClick(event)">
    <button class="close-button" onclick="closeModal()">&times;</button>
    <img id="modal-image" src="" alt="Preview" />
</div>

<script>
    function previewFoto(src) {
        const modal = document.getElementById('modal-preview');
        const modalImg = document.getElementById('modal-image');
        modalImg.src = src;
        modal.style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modal-preview').style.display = 'none';
    }

    function handleBackdropClick(e) {
        if (e.target.id === 'modal-preview') {
            closeModal();
        }
    }
</script>