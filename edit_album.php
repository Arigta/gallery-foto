<?php
require_once 'config.php';

if (isset($_GET['albumid'])) {
    $albumid = $_GET['albumid'];
    $sql_album = "SELECT namaalbum, deskripsi FROM album WHERE albumid = ?";
    $stmt_album = $conn->prepare($sql_album);
    $stmt_album->bind_param("i", $albumid);
    $stmt_album->execute();
    $result = $stmt_album->get_result();
    $album = $result->fetch_assoc();
    $stmt_album->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $namaalbum = $_POST['namaalbum'];
    $deskripsi = $_POST['deskripsi'];

    $sql_update = "UPDATE album SET namaalbum = ?, deskripsi = ? WHERE albumid = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $namaalbum, $deskripsi, $albumid);
    $stmt_update->execute();
    $stmt_update->close();

    header("Location: album.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
</head>
<body>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h3>Edit Album</h3>
            </div>
            <div class="card-body">
                <form action="edit_album.php?albumid=<?php echo $albumid; ?>" method="post">
                    <div class="form-group">
                        <label for="namaalbum" class="font-weight-bold">Nama Album</label>
                        <input type="text" name="namaalbum" id="namaalbum" class="form-control" value="<?php echo htmlspecialchars($album['namaalbum']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi" class="font-weight-bold">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required><?php echo htmlspecialchars($album['deskripsi']); ?></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success mt-3">Simpan Perubahan</button>
                        <a href="album.php" class="btn btn-secondary mt-3">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
