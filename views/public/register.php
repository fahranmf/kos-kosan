<?php require_once 'views/templates/header.php'; ?>

<!-- Section Register -->
<div class="auth-card">
    <h2 class="text-center">Register</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    
    <form action="index.php?page=register" method="POST" class="w-50 mx-auto">
        <div class="form-group">
            <label for="nama_penyewa" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_penyewa" name="nama_penyewa" required>
        </div>
        <div class="form-group">
            <label for="no_telp_penyewa" class="form-label">No Telepon</label>
            <input type="text" class="form-control" id="no_telp_penyewa" name="no_telp_penyewa" required>
        </div>
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email_penyewa" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password_penyewa" required>
        </div>
        <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>

    <div class="auth-footer">
        <p>Sudah punya akun? <a href="index.php?page=login">Login sekarang</a></p>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>
