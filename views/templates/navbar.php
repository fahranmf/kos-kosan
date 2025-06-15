<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="navbar-wrapper">
        <nav class="navbar">
            <h2>Kos Putra Agan</h2>
            <div class="nav-links">
                <?php
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="index.php?page=cek_status">Akun Saya</a>';
                }
                ?>
                <a href="index.php?page=home#home">Beranda</a>
                <a href="index.php?page=home#rooms">Kamar</a>
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
</body>

</html>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");


    .navbar-wrapper {
        font-family: "Poppins", sans-serif !important;
        top: 0;
        width: 100%;
        z-index: 1000;
        background-color: #006a71;
        position: fixed;
    }

    .navbar {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 25px;
    }


    .navbar h2 {
        margin: 0;
        font-size: 1.5rem;
        color: white;
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin-left: 1.5rem !important;
        font-size: 16px !important;
        font-weight: 800;

    }

    .navbar .nav-links {
        display: flex;
    }

    .navbar a:hover {
        text-decoration: underline;
    }

    .btn-nav-logout {
        background-color: #f2efe7;
        padding: 10px;
        border-radius: 15px;
        color: #006a71;
        font-weight: bold;
    }

    .btn-nav-login {
        background-color: #eac86a;
        padding: 10px;
        border-radius: 15px;
        color: black;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .navbar {
            display: none;
        }

        .container-custom {
            margin: 0;
        }
}
</style>