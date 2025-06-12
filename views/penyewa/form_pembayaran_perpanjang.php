<?php include 'views/templates/public2.php'; ?>

<div class="card">
    <h2>Form Pembayaran <br> Perpanjangan Kos</h2>

    <?php if (isset($_SESSION['errorMsg'])): ?>
        <p style="color:red; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['errorMsg']) ?>
            <?php unset($_SESSION['errorMsg']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php?page=submit_pembayaran_perpanjang" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_sewa" value="<?= htmlspecialchars($data['id_sewa']) ?>">
        
        <div class="form-group">
            <label>Tanggal Selesai Baru:</label>
            <input type="text" name="tanggal_selesai" value="<?= htmlspecialchars($data['tanggal_baru']) ?>" readonly>
        </div>


        <div class="form-group">
            <label for="jumlah_bayar">Jumlah Bayar</label>
            <input type="text" id="jumlah_bayar_display" readonly>
            <input type="hidden" id="jumlah_bayar" name="jumlah_bayar" required>
        </div>

        <!-- Hidden harga kamar -->
        <input type="hidden" id="harga_kamar" value="<?= htmlspecialchars($data['harga']) ?>">

        <div class="form-group">
            <label for="metode_pembayaran">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" required>
                <option value="E-Wallet">E-Wallet</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Cash">Cash</option>
            </select>
        </div>


        <div class="form-group">
            <label for="bukti_pembayaran">Bukti Pembayaran (upload file)</label>
            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*,application/pdf" required>
        </div>

        <button type="submit" name="bayar">Submit Pembayaran</button>
    </form>
</div>

<!-- Pindahkan script ke bawah -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hargaString = <?= json_encode($data['harga']) ?>; // hasilnya misal: "1200000.00"
        const harga = parseInt(hargaString); // buang koma dan convert ke int

        const jumlahDisplay = document.getElementById('jumlah_bayar_display');
        const jumlahHidden = document.getElementById('jumlah_bayar');

        function formatRupiah(angka) {
            return 'Rp. ' + angka.toLocaleString('id-ID');
        }

        jumlahDisplay.value = formatRupiah(harga);
        jumlahHidden.value = harga;
    });
</script>