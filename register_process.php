<?php
// Koneksi ke database
require_once 'config.php';

// Ambil data dari form
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$email = $_POST['email'];
$namalengkap = $_POST['namalengkap'];
$alamat = $_POST['alamat'];

// Gambar default untuk profil
$default_profile_picture = 'img/Profile-Transparent.png';

// Insert ke database
$sql = "INSERT INTO user (username, password, email, namalengkap, alamat, profile_picture)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $username, $password, $email, $namalengkap, $alamat, $default_profile_picture);

if ($stmt->execute()) {
    // Registrasi berhasil, alihkan ke halaman login
    header("Location: login.php?message=Registrasi berhasil, silakan login.");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah pengalihan
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
