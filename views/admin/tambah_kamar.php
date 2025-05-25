<!-- views/admin/tambah_kamar.php -->
<?php $title = 'Tambah Kamar - Kos Putra Agan'; ?>
<?php include 'views/templates/public2.php'; ?>


<div class="card">
    <h2>Tambah Kamar</h2>

    <form action="index.php?page=tambah_kamar" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="tipe_kamar">Tipe Kamar</label>
            <select name="tipe_kamar" id="tipe_kamar" required>
                <option value="A">Tipe A</option>
                <option value="B">Tipe B</option>
                <option value="C">Tipe C</option>
            </select>
        </div>

        <div class="form-group">
            <label for="harga_perbulan">Harga Perbulan</label>
            <input type="number" name="harga_perbulan" required>
        </div>

        <div class="form-group">
            <label for="status">Status Kamar</label>
            <select name="status" id="status" required>
                <option value="Kosong">Kosong</option>
                <option value="Isi">Isi</option>
            </select>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" required></textarea>
        </div>

        <div class="form-group">
            <label for="fasilitas">Fasilitas</label>
            <input type="text" name="fasilitas" required>
        </div>

        <div class="form-group">
            <label for="foto_kos">Foto Kamar</label>
            <input type="file" name="foto_kos" required>
        </div>

        <button type="submit">Tambah Kamar</button>
    </form>

</div>