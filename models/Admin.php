<?php
// models/Admin.php

require_once 'config/database.php';

class Admin {
    public static function findByEmail($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE email_admin = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchObject(self::class);
    }
}
