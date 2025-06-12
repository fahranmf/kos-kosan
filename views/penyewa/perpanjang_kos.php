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
            <!-- Layer kunci -->
            <h2>Perpanjang Kos</h2>
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
            <div
                style="padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                <p>Formulir atau tombol perpanjang kos di sini.</p>
                <button id="btn-perpanjang" disabled>Perpanjang Sekarang</button>
            </div>

            <!-- Konten perpanjang -->
            <div id="modal-perpanjang" class="modal">
                <div class="modal-content">
                    <h3>Konfirmasi Perpanjangan Kos</h3>
                    <form action="index.php?page=form_perpanjang_kos" method="POST">
                        <input type="hidden" name="id_sewa" value="<?= $data['id_sewa'] ?>">

                        <!-- Tanggal -->
                        <label>Tanggal Selesai Sekarang:</label>
                        <input type="text" readonly value="<?= $data['tanggal_selesai'] ?>">

                        <?php
                        use Carbon\Carbon;
                        $tglBaru = Carbon::parse($data['tanggal_selesai'])->addMonth()->format('Y-m-d');
                        ?>
                        <label>Tanggal Selesai Baru:</label>
                        <input type="text" readonly name="tanggal_selesai_baru" value="<?= $tglBaru ?>">

                        <!-- Ganti kamar -->
                        <label><input type="checkbox" id="check-ganti-kamar" name="ganti_kamar"> Ingin ganti
                            kamar?</label>

                        <div id="pilihan-kamar" style="display: none;">

                            <!-- Pilih Tipe Kamar -->
                            <label for="tipe_kamar">Pilih Tipe Kamar:</label>
                            <select id="tipe_kamar" name="tipe_kamar">
                                <option value="">-- Pilih --</option>
                                <option value="A">Tipe A</option>
                                <option value="B">Tipe B</option>
                                <option value="C">Tipe C</option>
                            </select>

                            <!-- Pilih No Kamar -->
                            <label for="id_kamar_baru">Pilih No Kamar:</label>
                            <select id="id_kamar_baru" name="id_kamar_baru">
                                <option value="">-- Pilih kamar --</option>
                            </select>

                            <!-- Harga Otomatis -->
                            <div id="harga_kamar"></div>
                        </div>

                        <button type="submit">Lanjutkan ke Pembayaran</button>
                        <button type="button" id="close-modal">Batal</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .modal,
    .modal-content,
    input,
    select {
        box-sizing: border-box;
    }

    #close-modal {
        padding: 10px 20px;
        background-color: lightgray;
        color: black;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    /* Modal background */
    .modal {
        display: none;
        /* default hidden */
        justify-content: center;
        align-items: center;
        position: fixed;
        z-index: 999;
        left: 0;
        top: 0;
        width: 100vw;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.active {
        display: flex;
    }

    /* Modal content */
    .modal-content {
        background-color: #fff;
        padding: 2rem;
        border-radius: 16px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        gap: 1rem;
        font-family: 'Segoe UI', sans-serif;
    }

    .modal-content h3 {
        margin: 0 0 1rem;
        text-align: center;
        font-size: 1.5rem;
        color: #333;
    }

    label {
        font-weight: 500;
        margin-bottom: 0.3rem;
        display: block;
    }

    #check-ganti-kamar {
        margin-bottom: 12px;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 0.6rem;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        margin-bottom: 20px;
    }

    #harga_kamar {
        font-weight: bold;
        margin-bottom: 20px;
        color: #444;
    }

    #btn-perpanjang, button[type="submit"] {
        background-color: #007BFF;
        padding: 10px 20px;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    #pilihan-kamar {
        margin-top: 1rem;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const layer = document.getElementById("layer-lock");
        const tombol = document.getElementById("btn-perpanjang");
        const modal = document.getElementById("modal-perpanjang");
        const checkbox = document.getElementById('check-ganti-kamar');
        const pilihanKamarDiv = document.getElementById('pilihan-kamar');
        const tipeSelect = document.getElementById('tipe_kamar');
        const noKamarSelect = document.getElementById('id_kamar_baru');
        const hargaDiv = document.getElementById('harga_kamar');
        const closeBtn = document.getElementById("close-modal");




        const kamarData = <?= json_encode($data['kamar_kosong']) ?>;

        checkbox.addEventListener('change', () => {
            pilihanKamarDiv.style.display = checkbox.checked ? 'block' : 'none';
            if (!checkbox.checked) {
                noKamarSelect.innerHTML = '<option value="">-- Pilih kamar --</option>';
                hargaDiv.innerHTML = '';
            }
        });

        tipeSelect.addEventListener('change', () => {
            const tipe = tipeSelect.value;
            const kamarFiltered = kamarData.filter(k => k.tipe_kamar === tipe);

            // Isi ulang dropdown kamar
            noKamarSelect.innerHTML = '<option value="">-- Pilih kamar --</option>';
            kamarFiltered.forEach(kamar => {
                const option = document.createElement('option');
                option.value = kamar.no_kamar;
                option.text = `No ${kamar.no_kamar}`;
                option.dataset.harga = kamar.harga_perbulan;
                noKamarSelect.appendChild(option);
            });

            hargaDiv.innerHTML = '';
        });

        noKamarSelect.addEventListener('change', () => {
            const selected = noKamarSelect.options[noKamarSelect.selectedIndex];
            const harga = selected.dataset.harga;
            if (harga) {
                hargaDiv.innerHTML = `<strong>Harga: Rp${parseInt(harga).toLocaleString('id-ID')}</strong>`;
            } else {
                hargaDiv.innerHTML = '';
            }
        });


        if (sisaHari > 5) {
            layer.style.display = "flex";
            tombol.setAttribute("disabled", true);
        } else {
            layer.style.display = "none";
            tombol.removeAttribute("disabled");

            tombol.addEventListener("click", () => {
                modal.style.display = "flex";
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });
        }
    });
</script>