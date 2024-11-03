<?php
// add_comment.php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userid']) || !isset($_POST['fotoid']) || !isset($_POST['comment'])) {
    exit(json_encode(['status' => 'error', 'message' => 'Invalid request']));
}

$userid = $_SESSION['userid'];
$fotoid = $_POST['fotoid'];
$comment = $_POST['comment'];
$tanggal = date('Y-m-d H:i:s');

// Cek apakah komentar yang sama sudah ada untuk pengguna dan foto yang sama
$sql_check = "SELECT * FROM komentarfoto WHERE fotoid = ? AND userid = ? AND isikomentar = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("iis", $fotoid, $userid, $comment);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Jika komentar yang sama sudah ada
    echo json_encode(['status' => 'error', 'message' => 'Comment already exists']);
} else {
    // Jika komentar belum ada, tambahkan komentar
    $sql = "INSERT INTO komentarfoto (fotoid, userid, isikomentar, tanggalkomentar) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $fotoid, $userid, $comment, $tanggal);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
}

$stmt_check->close();
$stmt->close();
$conn->close();
?>
