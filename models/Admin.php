<?php
// models/Admin.php

require_once 'config/database.php';

class Admin
{
    public static function findByEmail($email)
    {
        $pdo = Database::getConnection(); // Memanggil koneksi database
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email_admin = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchObject(self::class);
    }

    public static function getProfilAdmin($id_admin)
    {
        $db = Database::getConnection();
        $query = "SELECT *
              FROM admin
              WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_admin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateNama($id_admin, $nama_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE admin SET nama_admin = ? WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$nama_baru, $id_admin]);
    }

    public static function updateEmail($id_admin, $email_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE admin SET email_admin = ? WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$email_baru, $id_admin]);
    }

    public static function updateTelp($id_admin, $telp_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE admin SET no_telp_admin = ? WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$telp_baru, $id_admin]);
    }

    public static function getPassword($id_admin)
    {
        $db = Database::getConnection();
        $query = "SELECT password_admin FROM admin WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id_admin]);
        return $stmt->fetchColumn();
    }

    public static function updatePassword($id_admin, $password_baru)
    {
        $db = Database::getConnection();
        $query = "UPDATE admin SET password = ? WHERE id_admin = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$password_baru, $id_admin]);
    }
}

