<?php
session_start(); // Mulai sesi

// Koneksi ke database
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mendapatkan data pengguna berdasarkan username
    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Password benar, simpan user ID ke session
            $_SESSION['userid'] = $user['userid'];
            $_SESSION['username'] = $user['username'];
            // Redirect ke halaman beranda
            header("Location: index.php");
            exit;
        } else {
            // Password salah
            $error = "Password salah.";
            header("Location: login.php?error=" . urlencode($error));
            exit;
        }
    } else {
        // Username tidak ditemukan
        $error = "Username tidak ditemukan.";
        header("Location: login.php?error=" . urlencode($error));
        exit;
    }

    $stmt->close();
} else {
    // Jika request method bukan POST
    header("Location: login.php");
    exit;
}

// Jangan tutup koneksi di sini
// $conn->close();
?>
