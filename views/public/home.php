<?php include 'views/templates/public.php'; ?>
<script>
    const hasError = <?= !empty($errorMsg) ? 'true' : 'false' ?>;
    const errorMsg = <?= json_encode($errorMsg ?? '') ?>;

    if (hasError) {
        // Buka modal otomatis kalau ada error (misal validasi tanggal kosong)
        document.getElementById('bookingModal').style.display = 'block';
    }
</script>


<div class="container-custom">
    <div class="navbar-wrapper">
        <nav class="navbar">
            <h1>Kos Putra Agan</h1>
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#">Kamar</a>
                <a href="#">Fasilitas</a>
                <a href="#">Kontak</a>
                <?php
                if (isset($_SESSION['user_id'])) {
                    // Kalau sudah login, tampilkan tombol logout
                    echo '<a href="index.php?page=cek_status">Akun Saya</a>';
                    echo '<a href="index.php?page=logout"><span class="btn-nav-logout">Logout</a>';
                } else {
                    // Kalau belum login, tampilkan login dan signup
                    echo '<a href="index.php?page=login"><span class="btn-nav-login">Login/SignUp</a>';
                }
                ?>
            </div>
        </nav>
    </div>

    <div class="carousel-container">
        <div class="carousel-slide active">
            <img src="../../uploads/foto_kos/foto1.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <div class="caption-content">
                    <h2 class="section-title">Tempat Kos Nyaman</h2>
                    <h1 class="carousel-heading">Mencari Kos Yang Nyaman?</h1>
                    <div class="carousel-buttons">
                        <a href="#" class="btn btn-primary">Our Rooms</a>
                        <a href="#" class="btn btn-light">Book A Room</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="#room" class="container-rooms">
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
                                <a class="btn btn-primary open-booking-modal" href="#"
                                    data-tipe="<?= $kamar['tipe_kamar'] ?>">Book Now</a>
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
<div id="bookingModal" class="modal"
    style="display:none; position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.6);">
    <div class="modal-content"
        style="background:#fff;padding:20px;max-width:400px;margin:10% auto;position:relative;border-radius:8px;">
        <span id="closeModalBtn"
            style="position:absolute;top:10px;right:15px;cursor:pointer;font-size:20px;">&times;</span>
        <form method="POST" action="index.php?page=booking_kamar">
            <input type="hidden" name="tipe_kamar" id="modalTipeKamar" value="">
            <label>Pilih No Kamar:</label><br>
            <select name="no_kamar" id="dropdownNoKamar" required>
                <option value=""></option>
            </select><br><br>
            <label>Tanggal Mulai:</label><br>
            <input type="date" name="tanggal_mulai" required><br><br>
            <label>Tanggal Selesai:</label><br>
            <input type="date" name="tanggal_selesai" required readonly><br><br>
            <button type="submit" class="btn btn-primary">Submit Booking</button>
            <?php if (!empty($errorMsg)): ?>
                <p style="color:red"><?= htmlspecialchars($errorMsg) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>





<?php include 'views/templates/footer.php'; ?>