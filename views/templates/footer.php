<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <footer class="site-footer">
  <div class="footer-container">
    <div class="footer-about">
      <h3>Kos Putra Agan</h3>
      <p>Tempat tinggal nyaman dan strategis untuk mahasiswa dan pekerja. Dilengkapi fasilitas lengkap dan lingkungan aman.</p>
    </div>
    <div class="footer-links">
      <h4>Menu</h4>
      <ul>
        <li><a href="index.php?page=home#home">Beranda</a></li>
        <li><a href="index.php?page=home#rooms">Kamar</a></li>
        <li><a href="index.php?page=home#fasilitas">Fasilitas</a></li>
        <li><a href="index.php?page=home##kontak">Kontak</a></li>
      </ul>
    </div>
    <div class="footer-contact">
      <h4>Hubungi Kami</h4>
      <p><i class="fas fa-phone"></i> 0812-3456-7890</p>
      <p><i class="fas fa-envelope"></i> info@kosagan.com</p>
      <div class="footer-sosmed">
        <a href="#"><i class="fab fa-whatsapp"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-facebook"></i></a>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <p>&copy; <?= date('Y') ?> Kos Putra Agan. All rights reserved.</p>
  </div>
</footer>
</body>
</html>

<style>
    .site-footer {
    background-color: #222;
    color: #eee;
    padding: 40px 20px 10px;
    font-family: 'Segoe UI', sans-serif;
}

.footer-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 20px;
    max-width: 1140px;
    margin: auto;
    text-align: center;
    padding: 0 40px;
}

.footer-container > div {
    flex: 1 1 250px;
}

.footer-about{
    text-align: left;
}

.footer-about h3 {
    margin-bottom: 10px;
    color: #fff;
}

.footer-about p {
    font-size: 14px;
    line-height: 1.6;
}

.footer-links h4,
.footer-contact h4 {
    color: #fff;
    margin-bottom: 10px;
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a {
    color: #ccc;
    text-decoration: none;
    transition: color 0.3s;
}

.footer-links a:hover {
    color: #fff;
}

.footer-contact p {
    font-size: 14px;
    margin: 5px 0;
}

.footer-contact i {
    margin-right: 8px;
    color: #007bff;
}

.footer-sosmed a {
    display: inline-block;
    color: #ccc;
    margin-right: 10px;
    font-size: 18px;
    transition: color 0.3s;
}

.footer-sosmed a:hover {
    color: #fff;
}

.footer-bottom {
    text-align: center;
    margin-top: 30px;
    border-top: 1px solid #444;
    padding-top: 10px;
    font-size: 14px;
    color: #aaa;
}

@media (max-width:768px){
    .footer-about{
        text-align: center;
    }
}

</style>