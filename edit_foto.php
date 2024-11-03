<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Cek apakah ada parameter `id` (ID foto yang akan diedit)
if (!isset($_GET['id'])) {
    echo "ID foto tidak ditemukan.";
    exit;
}

$fotoid = $_GET['id'];

// Ambil data foto berdasarkan fotoid
$sql = "SELECT * FROM foto WHERE fotoid = ? AND userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $fotoid, $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Foto tidak ditemukan atau Anda tidak memiliki izin untuk mengedit foto ini.";
    exit;
}

$foto = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judulFoto = $_POST['judulFoto'];
    $deskripsiFoto = $_POST['deskripsiFoto'];
    $albumid = $_POST['album'];

    // Proses file gambar baru jika diunggah
    if (isset($_FILES['fileFoto']) && $_FILES['fileFoto']['error'] === UPLOAD_ERR_OK) {//Pemeriksaan
        $targetDir = "uploads/"; // Direktori penyimpanan gambar
        $fileName = basename($_FILES['fileFoto']['name']);
        $targetFilePath = $targetDir . $fileName;
        
        // Pindahkan file yang diunggah ke folder tujuan
        if (move_uploaded_file($_FILES['fileFoto']['tmp_name'], $targetFilePath)) {
            // Hapus gambar lama (opsional)
            if (!empty($foto['lokasifile']) && file_exists($foto['lokasifile'])) {
                unlink($foto['lokasifile']);
            }
            
            // Update data foto di database
            $sql_update = "UPDATE foto SET judulfoto = ?, deskripsifoto = ?, albumid = ?, lokasifile = ? WHERE fotoid = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssisi", $judulFoto, $deskripsiFoto, $albumid, $targetFilePath, $fotoid);
        } else {
            echo "Terjadi kesalahan saat mengunggah file.";
            exit;
        }
    } else {
        // Update data tanpa mengubah file gambar
        $sql_update = "UPDATE foto SET judulfoto = ?, deskripsifoto = ?, albumid = ? WHERE fotoid = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssii", $judulFoto, $deskripsiFoto, $albumid, $fotoid);
    }

    // Eksekusi perbaruan data
    if ($stmt_update->execute()) {
        echo "Foto berhasil diperbarui.";
        header("Location: foto.php");
        exit;
    } else {
        echo "Terjadi kesalahan saat memperbarui foto.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Foto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Foto</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="judulFoto">Judul Foto</label>
                <input type="text" class="form-control" id="judulFoto" name="judulFoto" value="<?php echo htmlspecialchars($foto['judulfoto']); ?>" required>
            </div>
            <div class="form-group">
                <label for="deskripsiFoto">Deskripsi Foto</label>
                <textarea class="form-control" id="deskripsiFoto" name="deskripsiFoto" rows="3" required><?php echo htmlspecialchars($foto['deskripsifoto']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="album">Masukkan ke Album</label>
                <select class="form-control" id="album" name="album">
                    <?php
                    $sqlAlbum = "SELECT albumid, namaalbum FROM album WHERE userid = ?";
                    $stmtAlbum = $conn->prepare($sqlAlbum);
                    $stmtAlbum->bind_param("i", $_SESSION['userid']);
                    $stmtAlbum->execute();
                    $resultAlbum = $stmtAlbum->get_result();

                    while ($album = $resultAlbum->fetch_assoc()) {
                        $selected = ($album['albumid'] == $foto['albumid']) ? 'selected' : '';
                        echo '<option value="' . $album['albumid'] . '" ' . $selected . '>' . htmlspecialchars($album['namaalbum']) . '</option>';
                    }
                    $stmtAlbum->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fileFoto">Ganti Foto (opsional)</label>
                <input type="file" class="form-control-file" id="fileFoto" name="fileFoto">
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="foto.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
