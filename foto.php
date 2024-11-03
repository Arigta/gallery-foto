<?php
session_start();
require_once 'config.php';
require_once 'helpers.php';
// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

$userid = $_SESSION['userid'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Anda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
    <style>
        .container-photo {
            display: flex;
            height: 100vh;
        }

        .nav-icons {
            width: 60px;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            border-right: 1px solid #ddd;
            position: fixed;
            height: 100%;
        }

        .content-wrapper {
            margin-left: 60px;
            display: flex;
            width: calc(100% - 60px);
        }

        .nav-icons .icon {
            font-size: 24px;
            margin-bottom: 20px;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.3s;
        }

        .nav-icons .icon.active {
            color: #007bff;
        }

        .form-container {
            width: 30%;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .photos-container {
            width: 70%;
            padding: 20px;
            overflow-y: auto;
            max-height: 80vh;
        }

        .photo-card {
            display: flex;
            flex-direction: row;
            margin-bottom: 20px;
            padding: 5px;
            align-items: stretch;
        }

        .photo-thumbnail {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 1px solid #ddd;
            margin-right: 15px;
        }

        .photo-info {
            flex: 1;
        }

        .photo-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .btn-action {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="container-photo">
        <!-- Bagian Ikon Navigasi Kiri -->
        <div class="nav-icons">
            <div class="logo mb-4">
                <a href="index.php">
                    <img src="img/logo.png" alt="Logo" style="width: 40px; height: 40px; margin-bottom: 20px;">
                </a>
            </div>
            <div class="icon <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                <a href="index.php" title="Beranda">🏠</a>
            </div>
            <div class="icon <?php echo basename($_SERVER['PHP_SELF']) == 'album.php' ? 'active' : ''; ?>">
                <a href="album.php" title="Album">📁</a>
            </div>
            <div class="icon <?php echo basename($_SERVER['PHP_SELF']) == 'foto.php' ? 'active' : ''; ?>">
                <a href="foto.php" title="Foto">📷</a>
            </div>
        </div>

        <!-- Wrapper untuk Form dan Foto -->
        <div class="content-wrapper">
            <!-- Bagian Form untuk Menambah Foto -->
            <div class="form-container">
                <h4>Tambah Foto</h4>
                <form action="upload_foto.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judulFoto">Judul Foto</label>
                        <input type="text" class="form-control" id="judulFoto" name="judulFoto" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsiFoto">Deskripsi Foto</label>
                        <textarea class="form-control" id="deskripsiFoto" name="deskripsiFoto" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="fileFoto">Unggah Foto</label>
                        <input type="file" class="form-control-file" id="fileFoto" name="fileFoto" required>
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
                                echo '<option value="' . $album['albumid'] . '">' . htmlspecialchars($album['namaalbum']) . '</option>';
                            }
                            $stmtAlbum->close();
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Foto</button>
                </form>
            </div>

            <!-- Bagian Kanan: Daftar Foto -->
            <div class="photos-container">
                <h4>Foto Anda</h4>
                <?php
                $sql = "SELECT f.*, u.namalengkap, u.profile_picture, a.namaalbum
                        FROM foto f 
                        JOIN user u ON f.userid = u.userid 
                        JOIN album a ON f.albumid = a.albumid
                        WHERE f.userid = ? 
                        ORDER BY f.tanggalunggah DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $_SESSION['userid']);
                $stmt->execute();
                $result = $stmt->get_result();

 while ($foto = $result->fetch_assoc()) {
                        echo '
            <div class="list-group-item">
                <div class="d-flex align-items-start">
                    <img src="' . htmlspecialchars($foto['lokasifile']) . '" class="img-thumbnail mr-3" alt="Foto" style="width: 120px; height: 120px; object-fit: cover;">
                    <div class="flex-grow-1">
                        <h4>' . htmlspecialchars($foto['judulfoto']) . '</h4>
                        <h6><strong>Album:</strong> ' . htmlspecialchars($foto['namaalbum']) . '</h6>
                        <h6><strong>Deskripsi:</strong> ' . htmlspecialchars($foto['deskripsifoto']) . '</h6>
                        <p class="text-muted"><small>Diunggah oleh: ' . htmlspecialchars($foto['namalengkap']) . '  ' . htmlspecialchars(string: formatTimeAgo($foto['tanggalunggah'])) . '</small></p>
                        <div class="d-flex">
                            <a href="edit_foto.php?id=' . $foto['fotoid'] . '" class="btn btn-outline-secondary btn-sm mr-2">Edit</a>
                            <a href="delete_foto.php?id=' . $foto['fotoid'] . '" class="btn btn-outline-danger btn-sm">Hapus</a>
                        </div>
                    </div>
                </div>
            </div>';
                    }
                $stmt->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>
