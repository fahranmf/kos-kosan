<!-- views/admin/edit_kamar.php -->
<?php $title = 'Edit Kamar - Kos Putra Agan'; ?>
<?php include 'views/templates/public2.php'; ?>

<?php
// Ambil data kamar berdasarkan ID yang dikirimkan melalui GET
if (isset($_GET['id'])) {
    $kamarId = $_GET['id'];
    $kamar = Kamar::findById($kamarId);
}
?>

<div class="card">
    <h2>Edit Data Kamar</h2>

    <form action="index.php?page=update_kamar" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $kamar['no_kamar'] ?>">

        <div class="form-group">
            <label for="tipe_kamar">Tipe Kamar</label>
            <select name="tipe_kamar" id="tipe_kamar" required>
                <option value="A" <?= $kamar['tipe_kamar'] == 'A' ? 'selected' : '' ?>>Tipe A</option>
                <option value="B" <?= $kamar['tipe_kamar'] == 'B' ? 'selected' : '' ?>>Tipe B</option>
                <option value="C" <?= $kamar['tipe_kamar'] == 'C' ? 'selected' : '' ?>>Tipe C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="harga_perbulan">Harga Perbulan</label>
            <input type="number" name="harga_perbulan" value="<?= $kamar['harga_perbulan'] ?>" required>
        </div>

        <div class="form-group">
            <label for="status">Status Kamar</label>
            <select name="status" id="status" required>
                <option value="Kosong" <?= $kamar['status'] == 'Kosong' ? 'selected' : '' ?>>Kosong</option>
                <option value="Isi" <?= $kamar['status'] == 'Isi' ? 'selected' : '' ?>>Isi</option>
            </select>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" required><?= $kamar['deskripsi'] ?></textarea>
        </div>

        <div class="form-group">
            <label for="fasilitas">Fasilitas</label>
            <input type="text" name="fasilitas" value="<?= $kamar['fasilitas'] ?>" required>
        </div>

        <div class="form-group">
            <label for="foto_kos">Foto Kamar</label>
            <!-- Menampilkan gambar lama jika ada -->
            <?php if (!empty($kamar['foto_kos'])): ?>
                <img src="uploads/foto_kos/<?= $kamar['foto_kos'] ?>" alt="Foto Kamar" style="max-width: 200px;">
                <input type="hidden" name="foto_kos_lama" value="<?= htmlspecialchars($kamar['foto_kos']); ?>">
            <?php endif; ?>
            <input type="file" name="foto_kos">
            <small>Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>


        <button type="submit">Update Kamar</button>
    </form>
</div>