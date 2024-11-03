<?php
// get_comments.php
session_start();
require_once 'config.php';
require_once 'helpers.php';

if (!isset($_GET['fotoid'])) {
    exit('No photo ID provided');
}

$fotoid = $_GET['fotoid'];
$sql = "SELECT k.*, u.namalengkap, u.profile_picture 
        FROM komentarfoto k 
        JOIN user u ON k.userid = u.userid 
        WHERE k.fotoid = ? 
        ORDER BY k.tanggalkomentar DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fotoid);
$stmt->execute();
$result = $stmt->get_result();

while ($comment = $result->fetch_assoc()):
?>
    <div class="comment mb-3">
        <div class="d-flex">
            <img src="<?php echo $comment['profile_picture']; ?>" class="rounded-circle mr-2" width="32" height="32">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                    <h6 class="mb-0"><?php echo $comment['namalengkap']; ?></h6>
                    <small class="text-muted"><?php echo formatTimeAgo($comment['tanggalkomentar']); ?></small>
                </div>
                <p class="mb-0"><small><?php echo htmlspecialchars($comment['isikomentar']); ?></small></p>
            </div>
        </div>
    </div>
<?php
endwhile;
$stmt->close();
$conn->close();
?>