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

    document.addEventListener('DOMContentLoaded', function () {
        const slides = document.querySelectorAll('.slide');
        const prevBtn = document.querySelector('.prev');
        const nextBtn = document.querySelector('.next');
        let currentIndex = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.remove('active');
                if (i === index) {
                    slide.classList.add('active');
                }
            });
        }

        prevBtn.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + slides.length) % slides.length;
            showSlide(currentIndex);
        });

        nextBtn.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        });
    });

</script>

<div class="container-custom">
    <?php include 'views/templates/navbar.php'; ?>
    <div id="home" class="carousel-container">
        <div class="carousel-slide active">
            <img src="uploads/foto_kos/foto1.jpg" alt="Carousel Image">
            <div class="carousel-caption">
                <div class="caption-content">
                    <h2 class="section-title">Kos Putra Agan</h2>
                    <h1 class="carousel-heading">Mencari Kos Yang Nyaman?</h1>
                    <div class="carousel-buttons">
                        <a href="#rooms" class="btn btn-book">Our Rooms</a>
                        <a href="#about" class="btn btn-light">About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="about" class="about-wrapper">
        <div class="about-container">
            <!-- Kiri: Text dan Statistik -->
            <div class="about-left">
                <h2 class="about-section-title">About Us</h2>
                <h3 class="about-main-title">Welcome to <br> Kos Putra Agan</span></h3>
                <p class="about-text">
                    Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos.
                    Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet.
                </p>
                <div class="about-stats">
                    <div class="about-box">
                        <i class="fa fa-hotel about-icon"></i>
                        <h2><?php echo $totalKos; ?></h2>
                        <p>Kamar Kos</p>
                    </div>
                    <div class="about-box">
                        <i class="fas fa-home about-icon"></i>
                        <h2><?php echo $totalTipeKamar; ?></h2>
                        <p>Tipe Kos</p>
                    </div>
                    <div class="about-box">
                        <i class="fa fa-users about-icon"></i>
                        <h2><?php echo $totalPenyewa; ?></h2>
                        <p>Penghuni <br> (Saat Ini)</p>
                    </div>
                </div>
            </div>

            <!-- Kanan: Gambar Slider -->
            <div class="about-right">
                <div class="slider">
                    <?php
                    $first = true;
                    foreach ($dataFasilitas as $fasilitas):
                        $class = $first ? 'slide active' : 'slide';
                        $first = false;
                    ?>
                        <img src="uploads/foto_info_kos/<?= htmlspecialchars($fasilitas['gambar']) ?>" class="<?= $class ?>" alt="Slide">
                    <?php endforeach; ?>

                    <button class="prev"><</button>
                    <button class="next">></button>
                </div>
            </div>
        </div>
        <!-- <a href="#" class="btn-about">Explore More</a> -->
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

    <!-- Lokasi / Map Section Start -->
    <div class="location-wrap">
        <div class="section-container">
            <div class="left-content">
                <h2 class="section-title">Our Location</h2>
                <h3>Find Us!</h3>
                <p>
                    Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit. Aliqu diam amet diam et eos.
                    Clita erat ipsum et lorem et sit, sed stet lorem sit clita duo justo magna dolore erat amet.
                </p>
            </div>
            <div class="right-content">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3965.3782500041752!2d106.82499527428435!3d-6.345038162076593!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69edb8ae8cfbdd%3A0x2ec23e3fed4355e5!2sKost%20Putra%20Agan!5e0!3m2!1sid!2sid!4v1748594515946!5m2!1sid!2sid"
                    class="map-frame" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>

    <section id="fasilitas" class="services-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Services</h2>
            <h3>Explore Our <span class="highlight">Services</span></h3>
        </div>
        <div class="services-grid">
            <?php 
                $iconMap = [
                    'Parkiran' => 'fa-car',
                    'WiFi Cepat' => 'fa-wifi',
                    'Warung Makan' => 'fa-utensils',
                    'Ruang Tamu' => 'fa-couch',
                    'Gazebo Santai' => 'fa-tree',
                    'Dapur Umum' => 'fa-dumpster-fire',
                ];
            ?>
            <?php foreach ($dataFasilitas as $fasilitas): ?>
                <?php 
                    // Tempatkan di dalam foreach agar tiap item dapat icon sesuai
                    $iconClass = isset($iconMap[$fasilitas['keterangan']]) ? $iconMap[$fasilitas['keterangan']] : 'fa-check';
                ?>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas <?= $iconClass ?>"></i>
                    </div>
                    <h5><?= htmlspecialchars($fasilitas['keterangan']) ?></h5>
                    <p><?= htmlspecialchars($fasilitas['deskripsi']) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </section>

    <div id="kontak" class="team-section">
        <div class="section-header">
            <h2 class="section-title">Our Team</h2>
            <h3>Explore Our <span class="highlight">Staffs</span></h3>
        </div>
        <div class="team-grid">
            <?php foreach ($dataAdmin as $Admin): ?>
                <div class="team-card">
                    <div class="team-image-wrapper">
                        <img src="assets/img/admin.jpg" alt="Team Member">
                        <div class="whatsapp-button">
                            <a href="https://wa.me/<?= htmlspecialchars($Admin['no_telp_admin']) ?>"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>
                    <div class="team-info">
                        <h5><?= htmlspecialchars($Admin['nama_admin']) ?></h5>
                        <small>Admin</small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>




<style>
    /* Container utama */
.services-section {
  padding: 60px 20px;
}

/* Container dalam */
.container {
  max-width: 1140px;
  margin: 0 auto;
  padding: 0 20px;
}



/* Grid layanan */
.services-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}


/* Card layanan */
.service-card {
  background-color: #fff;
  border: 1px solid #e0e0e0;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  text-decoration: none;
  color: #333;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Icon layanan */
.service-icon {
  width: 60px;
  height: 60px;
  margin: 0 auto 15px;
  border: 2px solid #007bff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.service-icon i {
  font-size: 24px;
  color: #007bff;
}

.service-card h5 {
  margin-bottom: 5px;
  font-size: 18px;
}

.service-card p {
  font-size: 14px;
  color: #666;
  margin: 0 0 10px;
}


.team-section {
    padding: 60px 40px;
    max-width: 1140px;
    margin: auto;
    text-align: center;
}

.team-header h6 {
    text-transform: uppercase;
    color: #007bff;
    margin-bottom: 10px;
    font-size: 14px;
    letter-spacing: 1px;
}

.team-header h1 {
    font-size: 32px;
    margin-bottom: 40px;
}

.team-header h1 span {
    color: #007bff;
    text-transform: uppercase;
}

.team-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.team-image-wrapper img {
    width: 100%;
    height: 250px; /* Atau sesuaikan, misal 250px */
    object-fit: cover;
    display: block;
}

.team-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s;
}

.team-card:hover {
    transform: translateY(-5px);
}

.team-image-wrapper {
    position: relative;
}

.team-image-wrapper img {
    width: 100%;
    display: block;
}

.whatsapp-button {
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #007bff;
    padding: 10px;
    transition: background 0.3s;
}

.whatsapp-button a {
    color: white;
    font-size: 18px;
    text-decoration: none;
}

.team-info {
    padding: 20px 10px;
}

.team-info h5 {
    margin: 10px 0 0;
    font-weight: bold;
}

.team-info small {
    color: #777;
}

/* Optional animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.team-card {
    animation: fadeInUp 0.6s ease both;
}

</style>



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
        align-items: center;
        animation: fadeIn 0.3s ease-in-out;
    }

    /* Modal box */
    .modal-content {
        background: #ffffff;
        padding: 30px 25px;
        width: 70%;
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
