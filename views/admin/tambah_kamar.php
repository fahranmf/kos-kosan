<!-- views/admin/tambah_kamar.php -->

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f2efe7;
        padding: 20px;
    }

    .card {
        background: #fff;
        max-width: 600px;
        margin: 20px auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 600;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"],
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        background: #fafafa;
    }

    textarea {
        resize: vertical;
        min-height: 100px;
    }

    button[type="submit"] {
        width: 100%;
        background: #48a6a7;
        color: white;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 10px;
    }

    button[type="submit"]:hover {
        background: #45a049;
    }

    @media (max-width: 600px) {
        .card {
            padding: 20px;
        }
    }
</style>

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