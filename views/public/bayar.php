<?php include 'views/templates/public2.php'; ?>
<script>
    const harga = <?= $harga_kamar ?>; // dari database

    function updateJumlahBayar() {
        const status = document.getElementById('status_pembayaran').value;
        const harga = parseFloat(document.getElementById('harga_kamar').value);
        const jumlahInput = document.getElementById('jumlah_bayar');

        if (status === 'Lunas') {
            jumlahInput.value = harga;
        } else if (status === 'Cicil') {
            jumlahInput.value = harga / 2;
        } else {
            jumlahInput.value = '';
        }
    }


</script>

<div class="card">
    <h2>Form Pembayaran</h2>

    <?php if (isset($_SESSION['errorMsg'])): ?>
        <p style="color:red; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['errorMsg']) ?>
            <?php unset($_SESSION['errorMsg']); ?>
        </p>
    <?php endif; ?>

    <form action="index.php?page=pembayaran&action=proses" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="status_pembayaran">Pilih Pembayaran</label>
            <select name="status_pembayaran" id="status_pembayaran" required onchange="updateJumlahBayar()">
                <option value="">-- Pilih --</option>
                <option value="Lunas">Lunas</option>
                <option value="Cicil">Cicil</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jumlah_bayar">Jumlah Bayar</label>
            <input type="number" id="jumlah_bayar" name="jumlah_bayar" readonly required>
        </div>

        <!-- ✅ Tambahkan elemen hidden untuk menyimpan harga -->
        <input type="hidden" id="harga_kamar" value="<?= $harga_kamar ?>">

        <div class="form-group">
            <label for="metode_pembayaran">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" required>
                <option value="E-Wallet">E-Wallet</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="Cash">Cash</option>
            </select>
        </div>

        <div class="form-group">
            <label for="tenggat_pembayaran">Tenggat Pembayaran</label>
            <input type="datetime-local" id="tenggat_pembayaran" name="tenggat_pembayaran">
        </div>

        <div class="form-group">
            <label for="bukti_pembayaran">Bukti Pembayaran (upload file)</label>
            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran" accept="image/*,application/pdf">
        </div>

        <button type="submit" name="bayar">Bayar</button>
    </form>
</div>