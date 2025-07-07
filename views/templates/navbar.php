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

            <!-- Tombol hamburger -->
            <div class="menu-toggle" onclick="toggleMenu()">
                ☰
            </div>

            <div class="nav-links" id="navLinks">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=cek_status">Akun Saya</a>
                <?php endif; ?>
                <a href="index.php?page=home#home">Beranda</a>
                <a href="index.php?page=home#rooms">Kamar</a>
                <a href="index.php?page=home#fasilitas">Fasilitas</a>
                <a href="index.php?page=home#kontak">Kontak</a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="index.php?page=logout"><span class="btn-nav-logout">Logout</span></a>
                <?php else: ?>
                    <a href="index.php?page=login"><span class="btn-nav-login">Login/SignUp</span></a>
                <?php endif; ?>
            </div>
        </nav>
    </div>

    <script>
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Tutup menu saat salah satu link diklik (mobile)
        /* document.addEventListener('DOMContentLoaded', () => {
            const navLinks = document.getElementById('navLinks');
            const menuToggle = document.getElementById('menuToggle');
            const navItems = navLinks.querySelectorAll('a');

            navItems.forEach(item => {
                item.addEventListener('click', () => {
                    // Cuma berlaku kalau menu lagi aktif (di mode mobile)
                    if (navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                        menuToggle.classList.remove('active');
                    }
                });
            });
        }); */
    </script>
</body>

</html>

<style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");


    .navbar-wrapper {
        font-family: "Poppins", sans-serif !important;
        top: 0;
        left: 0;
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

    /* Default: desktop */
    .navbar h2 {
        margin: 0;
        font-size: 1.5rem;
        color: white;
        text-align: left;
        /* default */
    }

    .navbar a {
        color: white;
        text-decoration: none;
        margin-left: 1.5rem !important;
        font-size: 16px !important;
        font-weight: 800;

    }

    .nav-links {
        display: flex;
    }


    .navbar a:hover {
        text-decoration: underline;
    }

    .btn-nav-logout {
        background-color: #f2efe7;
        padding: 10px;
        border-radius: 10px;
        color: #006a71;
        font-weight: bold;
    }

    .btn-nav-login {
        background-color: #eac86a;
        padding: 10px;
        border-radius: 10px;
        color: black;
        font-weight: bold;
    }

    .menu-toggle {
        display: none;
        font-size: 2rem;
        color: white;
        cursor: pointer;
    }

    /* Responsive behavior */
    @media (max-width: 768px) {
        .menu-toggle {
            display: block;
        }

        .nav-links {
            flex-direction: column;
            position: absolute;
            top: 70px;
            left: 0;
            width: 100%;
            background-color: #006a71;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
            z-index: 1;
        }

        .nav-links.active {
            display: flex;
            max-height: 500px;
        }

        .nav-links a {
            padding: 1rem;
            text-align: center;
            border-top: 1px solid #ffffff22;
        }

        .navbar {
            flex-direction: row;
            align-items: center;
            position: relative;
            justify-content: space-between;
        }

        .navbar h2 {
            text-align: center;
            width: 100%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }


        .menu-toggle {
            z-index: 1;
            /* biar gak ketiban sama h2 */
        }

        .menu-toggle {
            display: block;
            position: absolute;
            right: 1.5rem;
            top: 50%;
            transform: translateY(-50%);
        }

        .btn-nav-login, .btn-nav-logout {
            padding: 5px 10px;
        }
    }
</style>