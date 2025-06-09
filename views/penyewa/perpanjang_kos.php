<?php $title = 'Perpanjang Kos Penyewa - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header_penyewa.php'; ?>

<script>
    var sisaHari = <?= $data['sisa_hari_int'] ?>;
</script>


<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php include('sidebar.php'); ?>

    <!-- Main content area -->
    <div class="main-content">
        
        <div class="fitur-perpanjang" style="position: relative; margin-top: 20px;">
            <h2>Perpanjang Kos</h2>
            <!-- Layer kunci -->
            <div id="layer-lock" style="
                display: none;
                position: absolute;
                top: 0; left: 0;
                width: 100%; height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 18px;
                z-index: 10;
                text-align: center;
                padding: 10px 0;
                border-radius: 8px;
            ">
                Fitur ini akan tersedia saat sisa hari sewa kos kamu tinggal 5 hari atau kurang.
            </div>

            <!-- Konten perpanjang -->
            <div style="padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <p>Formulir atau tombol perpanjang kos di sini.</p>
                <button id="btn-perpanjang" disabled>Perpanjang Sekarang</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const layer = document.getElementById("layer-lock");
        const tombol = document.getElementById("btn-perpanjang");

        if (sisaHari > 5) {
            layer.style.display = "flex";
            tombol.setAttribute("disabled", true);
        } else {
            layer.style.display = "none";
            tombol.removeAttribute("disabled");
        }
    });
</script>
