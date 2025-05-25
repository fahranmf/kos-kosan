<!-- views/public/login.php -->
<?php $title = 'Login - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header.php'; ?>

<!-- Section Login -->
<div class="auth-card">
<h2>Login Kos Putra Agan</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <form action="../../index.php?page=login" method="POST" class="w-50 mx-auto">
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn">Login</button>
    </form>
    
    <div class="auth-footer">
        <p>Belum punya akun? <a href="../../index.php?page=register">Daftar sekarang</a></p>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>
