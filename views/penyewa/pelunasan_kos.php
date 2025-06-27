<?php include 'views/templates/public2.php'; ?>

<div class="card">
    <h2>Form Pembayaran <br> Pelunasan Kos</h2>


    <form action="index.php?page=submit_pembayaran_pelunasan" method="POST" enctype="multipart/form-data">
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