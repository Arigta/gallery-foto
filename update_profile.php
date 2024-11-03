<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['userid'];
$namalengkap = $_POST['namalengkap'];

// Update nama lengkap
$sql = "UPDATE user SET namalengkap = ? WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $namalengkap, $userid);
$stmt->execute();
$stmt->close();

// Proses foto profil
if (!empty($_POST['cropped_image'])) {
    try {
        // Validasi data base64
        $croppedImageData = $_POST['cropped_image'];
        if (strpos($croppedImageData, 'data:image') === false) {
            throw new Exception('Invalid image data');
        }

        // Decode base64
        list($type, $croppedImageData) = explode(';', $croppedImageData);
        list(, $croppedImageData) = explode(',', $croppedImageData);
        $croppedImageData = base64_decode($croppedImageData);

        if ($croppedImageData === false) {
            throw new Exception('Failed to decode image data');
        }

        // Buat direktori jika belum ada
        $uploadDir = __DIR__ . "/img/profile/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Simpan file dengan nama unik
        $fileName = "profile_" . $userid . "_" . time() . ".png";
        $filePath = $uploadDir . $fileName;
        
        if (file_put_contents($filePath, $croppedImageData) === false) {
            throw new Exception('Failed to save image file');
        }

        // Update database dengan path relatif
        $dbFilePath = "img/profile/" . $fileName;
        $sql = "UPDATE user SET profile_picture = ? WHERE userid = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $dbFilePath, $userid);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update database');
        }
        $stmt->close();

        // Set session variable untuk refresh foto profil
        $_SESSION['profile_picture'] = $dbFilePath;
        
    } catch (Exception $e) {
        // Log error
        error_log("Profile update error: " . $e->getMessage());
        header("Location: index.php?error=profile_update_failed");
        exit;
    }
}

header("Location: index.php?success=profile_updated");
exit;
?>