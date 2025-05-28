<?php include 'views/templates/public.php'; ?>
<?php

$errorMsg = $_SESSION['errorMsg'] ?? null;
$lastTipeKamar = $_SESSION['lastTipeKamar'] ?? '';
$shouldOpenModal = isset($_GET['openModal']) && $_GET['openModal'] == '1';


// Setelah ambil, hapus supaya gak terus muncul
unset($_SESSION['errorMsg'], $_SESSION['lastTipeKamar']);

?>

<script>
    window.bookingModalError = {
        hasError: <?= !empty($errorMsg) ? 'true' : 'false' ?>,
        errorMsg: <?= json_encode($errorMsg ?? '') ?>,
        shouldOpenModal: <?= isset($_GET['openModal']) ? 'true' : 'false' ?>,
        tipeKamar: <?= json_encode($_SESSION['last_tipe_kamar'] ?? '') ?>
    };
</script>

</script>





<div class="container-custom">
    <div class="navbar-wrapper">
        <nav class="navbar">
            <h1>Kos Putra Agan</h1>
            <div class="nav-links">
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="index.php?page=cek_status">Akun Saya</a>';
                }
                ?>
                <a href="#home">Beranda</a>
                <a href="#rooms">Kamar</a>
                <a href="#">Fasilitas</a>
                <a href="#">Kontak</a>
                <?php
                if (isset($_SESSION['user_id'])) {
                    // Kalau sudah login, tampilkan tombol logout
                    echo '<a href="index.php?page=logout"><span class="btn-nav-logout">Logout</a>';
                } else {
                    // Kalau belum login, tampilkan login dan signup
                    echo '<a href="index.php?page=login"><span class="btn-nav-login">Login/SignUp</a>';
                }
                ?>
            </div>
        </nav>
    </div>

    <div id="home" class="carousel-container">
        <div class="carousel-slide active">
            <img src="uploads/foto_kos/foto1.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <div class="caption-content">
                    <h2 class="section-title">Tempat Kos Nyaman</h2>
                    <h1 class="carousel-heading">Mencari Kos Yang Nyaman?</h1>
                    <div class="carousel-buttons">
                        <a href="#rooms" class="btn btn-book">Our Rooms</a>
                        <a href="#" class="btn btn-light">Book A Room</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="rooms" class="container-rooms">
        <div class="section-header">
            <h2 class="section-title">Our Rooms</h2>
            <h3>Explore Our <span class="highlight">Rooms</span></h3>
        </div>
        <div class="room-list">
            <?php foreach ($kamarList as $kamar): ?>
                <div class="room-card">
                    <div class="room-image">
                        <img src="uploads/foto_kos/<?= htmlspecialchars($kamar['foto_kos']) ?> " alt="Room Image">
                        <small class="price-tag">Rp <?= number_format($kamar['harga_perbulan'], 0, ',', '.') ?> /
                            Bulan</small>
                    </div>
                    <div class="room-content">
                        <div class="room-header">
                            <h5>Tipe <?= htmlspecialchars($kamar['tipe_kamar']) ?></h5>
                        </div>
                        <?php
                        $fasilitasList = array_map('trim', explode(',', strtolower($kamar['fasilitas'])));
                        ?>
                        <div class="room-features">
                            <?php if (in_array('bed', $fasilitasList)): ?>
                                <small><i class="fa fa-bed"></i> Bed</small>
                            <?php endif; ?>
                            <?php if (in_array('kamar mandi dalam', $fasilitasList)): ?>
                                <small><i class="fas fa-restroom"></i> Kamar Mandi Dalam</small>
                            <?php endif; ?>
                            <?php if (in_array('wifi', $fasilitasList)): ?>
                                <small><i class="fa fa-wifi"></i> Wifi</small>
                            <?php endif; ?>
                            <?php if (in_array('kipas angin', $fasilitasList)): ?>
                                <small><i class="fas fa-fan"></i> Kipas Angin</small>
                            <?php endif; ?>
                            <?php if (in_array('meja belajar', $fasilitasList)): ?>
                                <small><i class="fas fa-chalkboard"></i> Meja Belajar</small>
                            <?php endif; ?>
                            <?php if (in_array('lemari', $fasilitasList)): ?>
                                <small><i class="fas fa-book-open"></i> Lemari</small>
                            <?php endif; ?>
                        </div>
                        <p class="description"><?= htmlspecialchars($kamar['deskripsi']) ?></p>
                        <p><strong>Sisa kamar:</strong> <?= $kamar['jumlah_kosong'] ?> tersedia</p>
                        <div class="room-buttons">
                            <?php if ($kamar['jumlah_kosong'] > 0): ?>
                                <a class="btn btn-book open-booking-modal" href="#" data-tipe="<?= $kamar['tipe_kamar'] ?>">Book
                                    Now</a>
                            <?php else: ?>
                                <a class="btn btn-disabled" href="#"
                                    style="pointer-events: none; background-color: #ccc; color: #777;">Book Now</a>
                            <?php endif; ?>
                            <a class="btn btn-dark" href="index.php?page=detail_kamar&tipe=<?= $kamar['tipe_kamar'] ?>">View
                                Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal HTML di bawah halaman -->
<div id="bookingModal" class="modal">
    <div class="modal-content">
        <span id="closeModalBtn" class="close-btn">&times;</span>
        <form method="POST" action="index.php?page=booking_kamar">
            <label for="tipe_kamar">Tipe Kamar:</label>
            <input type="text" name="tipe_kamar" id="modalTipeKamar" required readonly>
            <label for="dropdownNoKamar">No Kamar:</label>
            <select name="no_kamar" id="dropdownNoKamar" required>
                <option value=""></option>
            </select>
            <label for="tanggal_mulai">Tanggal Mulai:</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" required>
            <label for="tanggal_selesai">Tanggal Selesai:</label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" required readonly>
            <?php if (!empty($errorMsg)): ?>
                <p style="color:red;"><?= htmlspecialchars($errorMsg) ?></p>
            <?php endif; ?>
            <button type="submit" class="btn-primary">Submit Booking</button>
        </form>
    </div>
</div>

<style>
    html {
        scroll-behavior: smooth;
    }

    .no-scroll {
        overflow: hidden;
    }


    /* Modal backdrop */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        z-index: 99999;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Modal box */
    .modal-content {
        background: #ffffff;
        padding: 30px 25px;
        max-width: 450px;
        margin: 10% auto;
        position: relative;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        animation: slideUp 0.4s ease-out;
    }

    /* Close button */
    .close-btn {
        position: absolute;
        top: 12px;
        right: 16px;
        font-size: 24px;
        font-weight: bold;
        color: #666;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .close-btn:hover {
        color: #000;
    }

    /* Input and select styles */
    /* Input dan select yang seragam */
    input[type="text"],
    input[type="date"],
    select {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
        margin-bottom: 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
        appearance: none;
        /* biar tampilan native-nya gak ganggu, opsional */
        -webkit-appearance: none;
        -moz-appearance: none;
        transition: border 0.2s ease;
    }


    input:focus,
    select:focus {
        border-color: #007bff;
        outline: none;
    }

    /* Submit button */
    .btn-primary {
        margin-top: 10px;
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }


    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(40px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>





<?php include 'views/templates/footer.php'; ?>