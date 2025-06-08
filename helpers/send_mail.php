<?php
require_once 'config/mail.php';

function sendEmail($to, $subject, $body) {
    $mail = getMailer();
    if (!$mail) return false;

    try {
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Gagal kirim email ke $to. Error: {$mail->ErrorInfo}");
        return false;
    }
}

// 1. Kirim OTP
function sendOTPEmail($to, $otp) {
    $subject = 'Verifikasi Email - OTP Kamu';
    $body = "
        <p>Halo,</p>
        <p>Kode OTP kamu adalah: <b>$otp</b></p>
        <p>Gunakan kode ini untuk verifikasi akun kamu.</p>
        <p>Kode ini berlaku selama 5 menit.</p>
    ";
    return sendEmail($to, $subject, $body);
}

// 2. Register Berhasil
function sendRegisterSuccessEmail($to) {
    $subject = 'Pendaftaran Berhasil';
    $body = "
        <p>Selamat! Pendaftaran kamu berhasil.</p>
        <p>Silakan login untuk melanjutkan.</p>
    ";
    return sendEmail($to, $subject, $body);
}

// 3. Sukses Bayar
function sendPaymentSuccessEmail($to, $nama) {
    $subject = 'Pembayaran Diterima';
    $body = "
        <p>Halo $nama,</p>
        <p>Kami telah menerima pembayaran kamu.</p>
        <p>Akun kamu saat ini <b>menunggu verifikasi</b>.</p>
    ";
    return sendEmail($to, $subject, $body);
}

// 4. Status Akun Diubah
function sendStatusUpdateEmail($to, $nama, $statusBaru) {
    $subject = 'Status Akun Diperbarui';
    $body = "
        <p>Halo $nama,</p>
        <p>Status akun kamu telah diubah menjadi: <b>$statusBaru</b>.</p>
        <p>Terima kasih telah menggunakan layanan kami!</p>
    ";
    return sendEmail($to, $subject, $body);
}
