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

// Proses untuk menambah album baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add_album') {
    $namaalbum = $_POST['namaalbum'] ?? '';
    $deskripsi = $_POST['deskripsi'] ?? '';
    $tanggaldibuat = date("Y-m-d");

    $sql = "INSERT INTO album (namaalbum, deskripsi, tanggaldibuat, userid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $namaalbum, $deskripsi, $tanggaldibuat, $userid);
    $stmt->execute();
    $stmt->close();

    header("Location: album.php");
    exit;
}

// Mendapatkan daftar album yang dibuat oleh pengguna
$sql_albums = "SELECT * FROM album WHERE userid = ? ORDER BY tanggaldibuat DESC";
$stmt_albums = $conn->prepare($sql_albums);
$stmt_albums->bind_param("i", $userid);
$stmt_albums->execute();
$result_albums = $stmt_albums->get_result();


// Tambahkan logika ini di bagian atas
// Proses untuk menghapus album
if (isset($_POST['action']) && $_POST['action'] == 'delete_album' && isset($_POST['albumid'])) {
    $albumid = $_POST['albumid'];

    // Hapus foto-foto yang terkait dengan album jika ada
    $sql_delete_photos = "DELETE FROM foto WHERE albumid = ?";
    $stmt_delete_photos = $conn->prepare($sql_delete_photos);
    $stmt_delete_photos->bind_param("i", $albumid);
    $stmt_delete_photos->execute();
    $stmt_delete_photos->close();

    // Hapus album
    $sql_delete_album = "DELETE FROM album WHERE albumid = ?";
    $stmt_delete_album = $conn->prepare($sql_delete_album);
    $stmt_delete_album->bind_param("i", $albumid);
    $stmt_delete_album->execute();
    $stmt_delete_album->close();

    header("Location: album.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Album Saya</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
    <style>
        .container-album {
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
            /* Untuk menggeser konten agar tidak tertimpa oleh menu kiri */
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

        .albums-container {
            width: 70%;
            padding: 20px;
            overflow-y: auto;
            max-height: 80vh;
        }

        .album-card {
            display: flex;
            flex-direction: row;
            margin-bottom: 20px;
            padding: 5px;
            align-items: stretch;


        }

        .album-images {
            width: 200px;
            height: 200px;
            display: flex;
            flex-wrap: wrap;
            position: relative;
        }

        .album-images img {
            object-fit: cover;
            border: 1px solid #fff;
            /* Border pemisah antar gambar */
            box-sizing: border-box;
        }

        .album-info {
            flex: 1;
            padding-left: 15px;
        }

        .album-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container-album">
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


        <!-- Wrapper untuk bagian Form dan Album -->
        <div class="content-wrapper">
            <!-- Bagian Tengah: Form untuk menambah album -->
            <div class="form-container">
                <h4>Buat Album Baru</h4>
                <form action="album.php" method="post">
                    <input type="hidden" name="action" value="add_album">
                    <div class="form-group">
                        <label for="namaalbum">Nama Album</label>
                        <input type="text" name="namaalbum" id="namaalbum" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambahkan Album</button>
                </form>
            </div>

            <!-- Bagian Kanan: Daftar album dengan foto preview -->
            <!-- Bagian Kanan: Daftar album -->
            <div class="container mt-4">
                <h4>Album Saya</h4>
                <?php while ($album = $result_albums->fetch_assoc()): ?>
                    <?php
                    $albumid = $album['albumid'];
                    // Query untuk mendapatkan foto terbaru dari album
                    $sql_foto = "SELECT lokasifile FROM foto WHERE albumid = ? ORDER BY tanggalunggah DESC LIMIT 3";
                    $stmt_foto = $conn->prepare($sql_foto);
                    $stmt_foto->bind_param("i", $albumid);
                    $stmt_foto->execute();
                    $result_foto = $stmt_foto->get_result();
                    $photos = [];
                    while ($foto = $result_foto->fetch_assoc()) {
                        $photos[] = $foto['lokasifile'];
                    }
                    $stmt_foto->close();
                    ?>

                    <div class="album-card card">
                        <!-- Bagian Gambar Album -->
                        <div class="album-images">
                            <span class="badge badge-secondary">Jumlah Foto: <?php echo count($photos); ?></span>
                            <?php if (count($photos) >= 3): ?>
                                <!-- Tampilan dengan 3 Gambar -->
                                <div style="width: 50%; height: 100%;">
                                    <img src="<?php echo htmlspecialchars($photos[0]); ?>" class="img-fluid" style="width: 100%; height: 100%;">
                                </div>
                                <div style="width: 50%; display: flex; flex-direction: column;  height: 100%; ">
                                    <img src="<?php echo htmlspecialchars($photos[1]); ?>" class="img-fluid" style="width: 100%; height: 50%;">
                                    <img src="<?php echo htmlspecialchars($photos[2]); ?>" class="img-fluid" style="width: 100%; height: 50%;">
                                </div>
                            <?php elseif (count($photos) == 2): ?>
                                <!-- Tampilan dengan 2 Gambar -->
                                <div style="width: 50%; height: 100%;">
                                    <img src="<?php echo htmlspecialchars($photos[0]); ?>" class="img-fluid" style="width: 100%; height: 100%;">
                                </div>
                                <div style="width: 50%; height: 100%;">
                                    <img src="<?php echo htmlspecialchars($photos[1]); ?>" class="img-fluid" style="width: 100%; height: 100%;">
                                </div>
                            <?php elseif (count($photos) == 1): ?>
                                <!-- Tampilan dengan 1 Gambar -->
                                <img src="<?php echo htmlspecialchars($photos[0]); ?>" class="img-fluid" style="width: 100%; height: 100%;">
                            <?php else: ?>
                                <!-- Jika tidak ada gambar -->
                                <div style="width: 100%; height: 100%; background-color: #ddd;"></div>
                            <?php endif; ?>
                        </div>

                        <!-- Bagian Data Album -->
                        <div class="album-info mb-2 ">
                            <h5 class="album-title p-2"><?php echo htmlspecialchars($album['namaalbum']); ?></h5>
                            <h6 class="text-muted mb-1 p-2">Dibuat pada: <?php echo htmlspecialchars(formatTimeAgo($album['tanggaldibuat'])); ?></h6>
                            <h6 class="album-description p-2"><?php echo htmlspecialchars($album['deskripsi']); ?></h6>


                            <!-- edit dan hapus-->
                            <div class="d-flex p-2">
                                <a href="edit_album.php?albumid=<?php echo $album['albumid']; ?>" style=" margin-right: 10px; "class="btn btn-warning btn-sm mt-2 me-3">Edit</a>

                                <form action="album.php" method="post" onsubmit="return confirmDelete();" class="d-inline">
                                    <input type="hidden" name="action" value="delete_album">
                                    <input type="hidden" name="albumid" value="<?php echo $album['albumid']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm mt-2">Hapus</button>
                                </form>
                            </div>






                        </div>
                    </div>
                <?php endwhile; ?>
            </div>




        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function confirmDelete() {
            return confirm("Apakah Anda yakin ingin menghapus album ini? Foto di dalam album juga akan dihapus jika Anda memilih opsi hapus.");
        }
    </script>

</body>

</html>

<?php
$stmt_albums->close();
$conn->close();
?>