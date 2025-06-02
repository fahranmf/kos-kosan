<?php $title = 'Register - Kos Putra Agan'; ?>
<?php require_once 'views/templates/header.php'; ?>

<!-- Section Register -->
<div class="auth-card">
    <h2 class="text-center">Register</h2>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error'];
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>


    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'];
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php
    $form_input = $_SESSION['form_input'] ?? [];
    unset($_SESSION['form_input']);
    ?>

    <?php
    $otpVerified = isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true;
    ?>

    <!-- Form kirim OTP -->
    <form action="index.php?page=register" method="POST" style="margin-bottom: 16px;">
        <div class="form-group">
            <label for="email_otp" class="form-label">Email</label>
            <div style="display: flex; gap: 8px;">
                <input type="email" class="form-control" id="email_otp" name="email_penyewa" required style="flex: 1;
                 <?= $otpVerified ? 'background-color: #e9ecef; color: #6c757d; border-color: #ced4da; cursor: not-allowed;' : '' ?>"
                    value="<?=
                        isset($form_input['email_penyewa']) ?
                        htmlspecialchars($form_input['email_penyewa']) :
                        (isset($_SESSION['otp_email']) ? htmlspecialchars($_SESSION['otp_email']) : '')
                        ?>" <?= $otpVerified ? 'readonly' : '' ?>>
                <?php if (!$otpVerified): ?>
                    <button type="submit" name="kirim_otp" value="1"
                        style="background-color: #48a6a7; color: #fff; border: none; padding: 8px 14px; border-radius: 4px; font-size: 14px; cursor: pointer;">
                        Kirim OTP
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- Form verifikasi OTP -->
    <?php if (!$otpVerified && isset($_SESSION['otp_sent'])): ?>
        <form action="index.php?page=register" method="POST" style="margin-bottom: 16px;">
            <div class="form-group">
                <label for="otp">Masukkan OTP</label>
                <div style="display: flex; gap: 8px;">
                    <input type="text" class="form-control" name="otp" required style="flex: 1;">
                    <input type="hidden" name="verify_otp" value="1">
                    <button type="submit"
                        style="background-color: #48a6a7; color: #fff; border: none; padding: 8px 14px; border-radius: 4px; font-size: 14px; cursor: pointer;">
                        Verifikasi <br>OTP
                    </button>
                </div>
            </div>
        </form>
    <?php endif; ?>


    <?php
    $formDisabledClass = $otpVerified ? '' : 'disabled-form';
    ?>

    <style>
        .disabled-form input,
        .disabled-form button {
            background-color: #f0f0f0 !important;
            color: #999 !important;
            border-color: #ccc !important;
            cursor: not-allowed;
        }

        .disabled-form input::placeholder {
            color: #ccc;
        }

        .disabled-form button {
            background-color:rgb(139, 135, 135) !important;
            color: black !important;
            opacity: 0.6;
        }
    </style>

    <!-- Form Register Setelah Verify-->
    <form action="index.php?page=register" method="POST" class="w-50 mx-auto  <?= $formDisabledClass ?>">
        <input type="hidden" name="email_penyewa" value="<?= htmlspecialchars($_SESSION['otp_email'] ?? '') ?>">
        <div class="form-group">
            <label for="nama_penyewa" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_penyewa" name="nama_penyewa" required
                value="<?= isset($form_input['nama_penyewa']) ? htmlspecialchars($form_input['nama_penyewa']) : '' ?>"
                <?= $otpVerified ? '' : 'readonly' ?>>
        </div>
        <div class="form-group">
            <label for="no_telp_penyewa" class="form-label">No Telepon</label>
            <input type="text" class="form-control" id="no_telp_penyewa" name="no_telp_penyewa" required
                value="<?= isset($form_input['no_telp_penyewa']) ? htmlspecialchars($form_input['no_telp_penyewa']) : '' ?>"
                <?= $otpVerified ? '' : 'readonly' ?>>
        </div>
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password_penyewa" required <?= $otpVerified ? '' : 'readonly' ?>>
        </div>
        <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                <?= $otpVerified ? '' : 'readonly' ?>>
        </div>
        <button type="submit" class="btn btn-primary w-100" <?= $otpVerified ? '' : 'disabled' ?>>
            Register
        </button>
    </form>

    <div class="auth-footer">
        <p>Sudah punya akun? <a href="index.php?page=login">Login sekarang</a></p>
    </div>
</div>

<?php require_once 'views/templates/footer.php'; ?>