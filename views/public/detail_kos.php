<?php $title = 'Detail Kamar - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_admin.php'; ?>
<?php include 'views/templates/navbar.php'; ?>

<style>
    body {
      background-color: #f2efe7;

        margin: 0;
        padding: 0;
        width: 100vw;
    }

    .container {
        background-color: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
    }

    h2 {
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
    }

    h3 {
        margin-top: 30px;
        font-size: 20px;
        color: #555;
        border-bottom: 2px solid #ddd;
        padding-bottom: 5px;
    }

    .galeri-scroll {
        display: flex;
        overflow-x: auto;
        gap: 14px;
        padding: 16px 0;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .foto-item {
        flex: 0 0 auto;
        width: 280px;
        scroll-snap-align: start;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out;
        cursor: pointer;
    }

    .foto-item:hover {
        transform: scale(1.05);
    }

    .foto-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        display: block;
    }

    .info {
        margin-top: 20px;
        line-height: 1.8;
        color: #444;
        font-size: 16px;
    }

    .info strong {
        color: #222;
    }

    .harga {
        font-size: 22px;
        font-weight: bold;
        color: #007bff;
        margin: 0;
    }

    .booking {
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-booking {
        display: inline-block;
        background-color: #007bff;
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(0, 170, 255, 0.25);
    }

    .btn-booking:hover {
        background-color: #008ecc;
        box-shadow: 0 8px 25px rgba(0, 140, 200, 0.3);
    }

    /* Modal Preview */
    .modal-preview {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.85);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        animation: fadeIn 0.1s ease-in-out;
    }

    .modal-preview img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 12px;
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
        transition: transform 0.3s ease;
    }

    .modal-preview .close-button {
        position: absolute;
        top: 20px;
        right: 30px;
        background-color: transparent;
        border: none;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        transition: transform 0.2s ease;
    }

    .modal-preview .close-button:hover {
        transform: scale(1.2);
    }

    .wrapper {
        margin: 80px 20px 0;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        gap: 24px;
        padding: 0 20px;
        box-sizing: border-box;
    }


    /* Kolom kiri: Detail Kamar */
    .detail-kamar {
        width: 72%;
        background-color: white;
        padding: 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        box-sizing: border-box;
    }

    /* Kolom kanan: Sidebar Kamar Kosong */
    .sidebar-kosong {
        width: 28%;
        background-color: white;
        padding: 20px;
        border-radius: 20px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        height: fit-content;
        position: sticky;
        box-sizing: border-box;
    }

    .sidebar-kosong h3 {
        font-size: 18px;
        color: #333;
        margin-bottom: 15px;
    }

    .kamar-kosong-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .kamar-kosong-list li {
        background-color: #f0f8ff;
        padding: 10px 14px;
        margin-bottom: 8px;
        border-radius: 10px;
        font-size: 15px;
        color: #007bff;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    @media (max-width: 768px) {
        .wrapper {
            flex-direction: column;
        }

        .detail-kamar,
        .sidebar-kosong {
            flex: 1 1 100%;
            width: 100%;
        }
    }


    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .container {
            padding: 20px;
        }

        .foto-item {
            width: 220px;
            height: 160px;
        }

        .foto-item img {
            height: 160px;
        }

        .modal-preview img {
            max-width: 95%;
            max-height: 80%;
        }

        .btn-booking {
            text-align: center;
        }

        .booking {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

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