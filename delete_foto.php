<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter `id` (ID foto yang akan dihapus)
if (!isset($_GET['id'])) {
    echo "ID foto tidak ditemukan.";
    exit;
}

$fotoid = $_GET['id'];

// Cek apakah foto milik pengguna yang sedang login
$sql = "SELECT * FROM foto WHERE fotoid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $fotoid, $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Foto tidak ditemukan atau Anda tidak memiliki izin untuk menghapus foto ini.";
    exit;
}

// Hapus data foto dari database
$sql_delete = "DELETE FROM foto WHERE fotoid = ?";
$stmt_delete = $conn->prepare($sql_delete);
$stmt_delete->bind_param("i", $fotoid);

if ($stmt_delete->execute()) {
    echo "Foto berhasil dihapus.";
    header("Location: foto.php");
    exit;
} else {
    echo "Terjadi kesalahan saat menghapus foto.";
}

$stmt->close();
$stmt_delete->close();
$conn->close();
?>
