<?php
require_once 'config/database.php'; // Pastikan koneksi Database

class Kamar
{
    // Dapatkan total kos
    public static function getTotalKos(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(*) AS total FROM kamar";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    // Ambil semua data kamar
    public static function getAllKamar(int $limit, int $offset): array
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM kamar ORDER BY no_kamar ASC LIMIT :limit OFFSET :offset";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cari kamar berdasarkan ID
    public static function findById($id)
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM kamar WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update data kamar
    public static function update($id, $foto_kos, $tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas)
    {
        $db = Database::getConnection();


        $query = "UPDATE kamar SET 
                    foto_kos = :foto_kos,
                    tipe_kamar = :tipe_kamar,
                    harga_perbulan = :harga_perbulan,
                    status = :status,
                    deskripsi = :deskripsi,
                    fasilitas = :fasilitas
                  WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':foto_kos', $foto_kos);
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':harga_perbulan', $harga_perbulan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':fasilitas', $fasilitas);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Tambah Kamar
    public static function insertKamar($tipe_kamar, $harga_perbulan, $status, $deskripsi, $fasilitas, $foto_kos)
    {
        $db = Database::getConnection();

        // Query untuk menambah kamar baru ke database
        $query = "INSERT INTO kamar (tipe_kamar, harga_perbulan, status, deskripsi, fasilitas, foto_kos) 
                  VALUES (:tipe_kamar, :harga_perbulan, :status, :deskripsi, :fasilitas, :foto_kos)";
        $stmt = $db->prepare($query);

        // Bind parameter
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->bindParam(':harga_perbulan', $harga_perbulan);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':fasilitas', $fasilitas);
        $stmt->bindParam(':foto_kos', $foto_kos);

        // Eksekusi query
        $stmt->execute();
    }

    // Hapus kamar
    public static function deleteById($id)
    {
        $db = Database::getConnection();
        $query = "DELETE FROM kamar WHERE no_kamar = :id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public static function getKamarPerTipeDenganJumlahKosong()
    {
        $db = Database::getConnection();
        $query = "SELECT 
                    tipe_kamar,
                    MAX(no_kamar) AS no_kamar,
                    MAX(foto_kos) AS foto_kos,
                    MAX(harga_perbulan) AS harga_perbulan,
                    MAX(deskripsi) AS deskripsi,
                    MAX(fasilitas) AS fasilitas,
                    SUM(status = 'Kosong') AS jumlah_kosong
                FROM kamar
                GROUP BY tipe_kamar
                ";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAvailableRoomByType($tipe_kamar)
    {
        $db = Database::getConnection();
        $query = "SELECT * FROM kamar WHERE tipe_kamar = :tipe_kamar AND status = 'Kosong'";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':tipe_kamar', $tipe_kamar);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getHargaByNoKamar($no_kamar)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT harga_perbulan FROM kamar WHERE no_kamar = ?");
        $stmt->execute([$no_kamar]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['harga_perbulan'] : null;
    }

    public static function getTipeKamar(): int
    {
        $db = Database::getConnection();
        $query = "SELECT COUNT(DISTINCT tipe_kamar) as total FROM `kamar`
                ";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public static function setStatusKamar($no_kamar, $status)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE kamar SET status = ? WHERE no_kamar = ?");
        return $stmt->execute([$status, $no_kamar]);
    }

    public static function getKamarKosongByTipe($tipe)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM kamar WHERE status = 'Kosong' AND tipe_kamar = ?");
        $stmt->execute([$tipe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getHargaByIdSewa($id_sewa)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT k.harga_perbulan 
            FROM sewa s
            JOIN kamar k ON s.no_kamar = k.no_kamar
            WHERE s.id_sewa = ?
        ");
        $stmt->execute([$id_sewa]);
        return $stmt->fetchColumn();
    }

    public static function getKamarKosong()
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM kamar WHERE status = 'Kosong'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getKamarByTipe($tipe_kamar)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT no_kamar, foto_kos, tipe_kamar, harga_perbulan, deskripsi, fasilitas
            FROM kamar
            WHERE tipe_kamar = ?
            LIMIT 1
        ");
        $stmt->execute([$tipe_kamar]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFotoByTipeKamar($no_kamar)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            SELECT f.nama_file 
            FROM foto_detail_kamar f
            JOIN kamar k ON k.no_kamar = f.no_kamar
            WHERE k.no_kamar = ?
        ");
        $stmt->execute([$no_kamar]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

}
?>