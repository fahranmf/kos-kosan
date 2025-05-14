<?php include 'views/templates/public.php'; ?>

<style>

</style>


<div class="container-custom">
    <div class="navbar-wrapper">
        <nav class="navbar">
            <h1>Kos Putra Agan</h1>
            <div class="nav-links">
                <a href="#">Beranda</a>
                <a href="#">Kamar</a>
                <a href="#">Fasilitas</a>
                <a href="#">Kontak</a>
            </div>
        </nav>
    </div>

    <div class="carousel-container">
        <div class="carousel-slide active">
            <img src="../../uploads/foto_kos/foto1.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <div class="caption-content">
                    <h2 class="section-title">Luxury Living</h2>
                    <h1 class="carousel-heading">Discover A Brand Luxurious Hotel</h1>
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
                            <div class="stars">
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                                <small class="fa fa-star text-primary"></small>
                            </div>
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
                        </div>
                        <p class="description"><?= htmlspecialchars($kamar['deskripsi']) ?></p>
                        <p><strong>Sisa kamar:</strong> <?= $kamar['jumlah_kosong'] ?> tersedia</p>
                        <div class="room-buttons">
                            <?php if ($kamar['jumlah_kosong'] > 0): ?>
                                <a class="btn btn-primary" href="index.php?page=booking_kamar&tipe=<?= $kamar['tipe_kamar'] ?>">Book Now</a>
                            <?php else: ?>
                                <a class="btn btn-disabled" href="#"
                                    style="pointer-events: none; background-color: #ccc; color: #777;">Book Now</a>
                            <?php endif; ?>
                            <a class="btn btn-dark" href="#">View Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>





<?php include 'views/templates/footer.php'; ?>