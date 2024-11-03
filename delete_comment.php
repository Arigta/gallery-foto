<?php
// delete_comment.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userid']) || !isset($_POST['commentid']) || !isset($_POST['fotoid'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Invalid request']));
}

$userid = $_SESSION['userid'];
$commentid = $_POST['commentid'];
$fotoid = $_POST['fotoid'];

// Periksa apakah pengguna yang sedang login adalah pemilik komentar
$sql = "SELECT * FROM komentarfoto WHERE komentarid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $commentid, $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $sql_delete = "DELETE FROM komentarfoto WHERE komentarid = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $commentid);

    if ($stmt_delete->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }

    $stmt_delete->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Anda tidak berhak menghapus komentar ini.']);
}

$stmt->close();
$conn->close();
