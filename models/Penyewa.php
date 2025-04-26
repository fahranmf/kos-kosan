<?php
// models/Penyewa.php

require_once 'config/database.php';

class Penyewa {
    public string $nama_penyewa;
    public string $no_telp_penyewa;
    public string $email_penyewa;
    public string $password_penyewa;
    public string $status_akun;

    public static function findByEmail($email) {
        $db = Database::getConnection();
        $query = "SELECT * FROM penyewa WHERE email_penyewa = :email LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    public function save(): void {
        $db = Database::getConnection();
        $query = "INSERT INTO penyewa (nama_penyewa, no_telp_penyewa, email_penyewa, password_penyewa, status_akun)
                  VALUES (:nama_penyewa, :no_telp_penyewa, :email_penyewa, :password_penyewa, :status_akun)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nama_penyewa', $this->nama_penyewa);
        $stmt->bindParam(':no_telp_penyewa', $this->no_telp_penyewa);
        $stmt->bindParam(':email_penyewa', $this->email_penyewa);
        $stmt->bindParam(':password_penyewa', $this->password_penyewa);
        $stmt->bindParam(':status_akun', $this->status_akun);
        $stmt->execute();
    }
}
