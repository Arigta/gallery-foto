<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['userid'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

if (isset($_POST['fotoid'])) {
    $userid = $_SESSION['userid'];
    $fotoid = $_POST['fotoid'];

    // Check if already liked
    $check_sql = "SELECT * FROM likefoto WHERE fotoid = ? AND userid = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $fotoid, $userid);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // Unlike
        $delete_sql = "DELETE FROM likefoto WHERE fotoid = ? AND userid = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("ii", $fotoid, $userid);
        
        if ($delete_stmt->execute()) {
            echo json_encode(['status' => 'unliked']);
        } else {
            echo json_encode(['error' => 'Failed to unlike']);
        }
        $delete_stmt->close();
    } else {
        // Like
        $insert_sql = "INSERT INTO likefoto (fotoid, userid, tanggallike) VALUES (?, ?, NOW())";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $fotoid, $userid);
        
        if ($insert_stmt->execute()) {
            echo json_encode(['status' => 'liked']);
        } else {
            echo json_encode(['error' => 'Failed to like']);
        }
        $insert_stmt->close();
    }
    
    $check_stmt->close();
} else {
    echo json_encode(['error' => 'No foto ID provided']);
}

$conn->close();
?>