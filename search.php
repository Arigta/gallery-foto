<?php
// search.php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userid'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if (!isset($_GET['q'])) {
    echo json_encode(['error' => 'No search term provided']);
    exit;
}

$search_term = '%' . $_GET['q'] . '%';

try {
    // Query untuk mencari foto berdasarkan judul foto atau nama lengkap pengguna
    $sql = "SELECT DISTINCT f.*, u.username, u.namalengkap, u.namalengkap, u.profile_picture 
            FROM foto f 
            JOIN user u ON f.userid = u.userid 
            WHERE f.judulfoto LIKE ? 
            OR u.namalengkap LIKE ? 
            ORDER BY f.tanggalunggah DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();

    $html = '';

    while ($row = $result->fetch_assoc()) {
        // Hitung jumlah like
        $sql_count_like = "SELECT COUNT(*) AS like_count FROM likefoto WHERE fotoid = ?";
        $stmt_like = $conn->prepare($sql_count_like);
        $stmt_like->bind_param("i", $row['fotoid']);
        $stmt_like->execute();
        $result_like = $stmt_like->get_result();
        $like_data = $result_like->fetch_assoc();
        $like_count = $like_data['like_count'];
        $stmt_like->close();
        
        // Hitung jumlah komentar
        $sql_count_comment = "SELECT COUNT(*) AS comment_count FROM komentarfoto WHERE fotoid = ?";
        $stmt_comment = $conn->prepare($sql_count_comment);
        $stmt_comment->bind_param("i", $row['fotoid']);
        $stmt_comment->execute();
        $result_comment = $stmt_comment->get_result();
        $comment_data = $result_comment->fetch_assoc();
        $comment_count = $comment_data['comment_count'];
        $stmt_comment->close();
        
        // Generate HTML untuk setiap hasil
        $html .= '
        <div class="post card mb-2">
            <div class="card-header d-flex align-items-center">
                <img src="' . htmlspecialchars($row['profile_picture']) . '" alt="User Avatar" class="rounded-circle" width="50" height="50">
                <div class="ml-3">
                    <h4>' . htmlspecialchars($row['namalengkap']) . '</h4>
                    <p class="text-muted">' . $row['tanggalunggah'] . '</p>
                </div>
            </div>
            <div class="card-body">
                <img src="' . htmlspecialchars($row['lokasifile']) . '" class="img-fluid mb-2" style="width: 100%; height: auto; object-fit: cover;" alt="Post Image">
                <h5>' . htmlspecialchars($row['judulfoto']) . '</h5>
                <p>' . htmlspecialchars($row['deskripsifoto']) . '</p>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button class="btn btn-outline-primary like-btn" data-fotoid="' . $row['fotoid'] . '">
                    Like <span class="like-count">' . $like_count . '</span>
                </button>
                <button class="btn btn-outline-secondary comment-btn" 
                    data-fotoid="' . $row['fotoid'] . '"
                    data-image="' . htmlspecialchars($row['lokasifile']) . '"
                    data-username="' . htmlspecialchars($row['username']) . '"
                    data-userimage="' . htmlspecialchars($row['profile_picture']) . '"
                    data-uploaddate="' . $row['tanggalunggah'] . '">
                    Komentar <span class="comment-count">' . $comment_count . '</span>
                </button>
            </div>
        </div>';
    }

    echo json_encode([
        'html' => $html
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}

$stmt->close();
$conn->close();
?>