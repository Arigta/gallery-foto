<?php
session_start(); // Memulai sesi

// Memastikan pengguna telah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Memasukkan file konfigurasi database
require_once 'config.php';

// Mengecek apakah form telah di-submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judulFoto = $_POST['judulFoto'];
    $deskripsiFoto = $_POST['deskripsiFoto'];
    $albumid = $_POST['album']; // Album tempat foto disimpan
    $userid = $_SESSION['userid']; // ID pengguna yang sedang login

    // Mengatur lokasi penyimpanan file foto
    $targetDir = "uploads/";
    $fileName = basename($_FILES["fileFoto"]["name"]);
    $targetFilePath = $targetDir . uniqid() . '_' . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Validasi file (hanya mengizinkan file gambar)
    $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowedTypes)) {
        // Proses upload file
        if (move_uploaded_file($_FILES["fileFoto"]["tmp_name"], $targetFilePath)) {
            // Query untuk memasukkan data foto ke dalam database
            $sql = "INSERT INTO foto (userid, albumid, judulfoto, deskripsifoto, lokasifile, tanggalunggah) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisss", $userid, $albumid, $judulFoto, $deskripsiFoto, $targetFilePath);

            if ($stmt->execute()) {
                // Jika berhasil, arahkan kembali ke halaman foto
                header("Location: foto.php");
                exit;
            } else {
                echo "Gagal menyimpan data foto.";
            }
            $stmt->close();
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    } else {
        echo "Maaf, hanya file dengan format JPG, JPEG, PNG, & GIF yang diperbolehkan.";
    }
}

$conn->close();
?>
