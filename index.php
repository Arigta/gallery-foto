<?php
session_start();

// Cek apakah pengguna sudah login, jika belum, arahkan ke login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
require_once 'config.php';
require_once 'helpers.php';


// Ambil informasi pengguna yang sedang login
$userid = $_SESSION['userid'];
$sql_user = "SELECT * FROM user WHERE userid = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $userid);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$stmt_user->close();

// Ambil semua postingan dari tabel foto
$sql_foto = "SELECT foto.*, user.username, user.namalengkap, user.profile_picture FROM foto JOIN user ON foto.userid = user.userid ORDER BY tanggalunggah DESC";
$result_foto = $conn->query($sql_foto);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Gallery</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">

    <link rel="stylesheet" href="css/style1.css">
    <style>
        /* Mempertahankan Layout Dasar dengan Peningkatan Desain */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --background-color: #f8f9fa;
            --text-color: #2c3e50;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            display: flex;
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            min-height: 100vh;
        }

        /* Left Column - 30% */
        .left-column {
            width: 25%;
            background: linear-gradient(135deg, #2c3e50, #3498db);
            padding: 20px;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .logo {
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.02);
        }

        .logo img {
            transition: transform 0.3s ease;
        }

        .logo:hover img {
            transform: rotate(5deg);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 12px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 5px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }

        /* Main Content - 40% */
        .main-content {
            width: 45%;
            margin-left: 27%;
            padding: 20px 30px;
            overflow-y: auto;
            height: 100vh;
        }

        .post.card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            transform: translateY(0);
            transition: all 0.3s ease;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        .post.card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 15px;
        }

        .card-header img {
            transition: transform 0.3s ease;
        }

        .card-header:hover img {
            transform: scale(1.1);
        }

        .card-body img {
            border-radius: 10px;
            transition: transform 0.3s ease;
        }

        .card-body img:hover {
            transform: scale(1.01);
        }

        /* Right Column - 30% */
        .right-column {
            width: 25%;
            padding: 20px;
            position: fixed;
            right: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .user-profile {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .other-users {
            margin-top: 20px;
        }

        .other-users .media {
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .other-users .media:hover {
            background: rgba(52, 152, 219, 0.1);
            transform: translateX(5px);
        }

        /* Button Styling */
        .btn {
            border-radius: 20px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-outline-primary {
            border-color: var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline-primary:hover {
            background: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Search Input Enhancement */
        .input-group {
            transition: all 0.3s ease;
        }

        #searchInput {
            border-radius: 20px 0 0 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        #searchInput::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .input-group-append .btn {
            border-radius: 0 20px 20px 0;
            background: rgba(255, 255, 255, 0.1);
            border: none;
        }

        /* Copyright Footer */
        .copyright {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 25%;
            background: rgba(44, 62, 80, 0.9);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 0.85rem;
            backdrop-filter: blur(5px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(52, 152, 219, 0.5);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(52, 152, 219, 0.7);
        }


        .like-btn {
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .like-btn i {
            transition: all 0.3s ease;
        }

        .like-btn.liked {
            background-color: #3B71CA;
            color: white;
            border-color: #3B71CA;
        }

        .like-btn.liked i {
            color: white;
            transform: scale(1.1);
        }

        .like-btn:not(.liked) i {
            color: #3B71CA;
        }

        .like-btn:hover {
            transform: translateY(-2px);
        }

        .like-btn.liked:hover {
            background-color: #2f5aa9;
            border-color: #2f5aa9;
        }

        /* Mobile Navigation Bar */
        .mobile-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            border-top: 1px solid #ddd;
            padding: 10px 0;
            z-index: 1000;
        }

        .mobile-nav .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .mobile-nav .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #333;
            text-decoration: none;
        }

        .mobile-nav .nav-item i {
            font-size: 24px;
            margin-bottom: 4px;
        }

        .mobile-header {
            display: none;
            padding: 10px 15px;
            background: white;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        /* Profile Modal Styles */
        .profile-modal {
            display: none;
            position: fixed;
            bottom: 60px;
            /* Positioned above the mobile nav */
            left: 0;
            /* Changed from right to left */
            width: 100%;
            /* Full width on mobile */
            background: white;
            border-radius: 10px 10px 0 0;
            /* Rounded corners only at top */
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            z-index: 1001;
            transform: translateY(100%);
            transition: transform 0.3s ease-out;
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            .left-column,
            .right-column {
                display: none;
            }

            .main-content {
                width: 100%;
                margin: 60px 0 70px 0;
                padding: 10px;
            }

            .mobile-nav,
            .mobile-header {
                display: block;
            }

            .post.card {
                margin-bottom: 15px;
            }

            .profile-modal {
                bottom: 60px;
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .profile-modal .user-profile {
                margin-bottom: 15px;
            }
        }

        /* Animation for Profile Modal */
        .profile-modal.show {
            display: block;
            transform: translateY(0);
        }

        /* Overlay for Profile Modal */
        .profile-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .profile-modal-overlay.show {
            display: block;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>

<body>
    <div class="left-column">
        <div class="logo mb-4 align-items-center">
            <div class="d-flex ">
                <img src="img/logo.png" alt="Logo" style="width: 40px; height: 40px; margin-bottom: 20px; margin-right:10px">
                <h2>My Gallery</h2>
            </div>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Beranda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="album.php">Album</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="foto.php">Foto</a>
            </li>
            <li class="nav-item mt-3">
                <form id="searchForm" class="search-form">
                    <div class="input-group">
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Cari foto atau pengguna...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </li>
        </ul>
        <div class="mt-auto">
            <form action="logout.php" method="post">
                <button type="submit" class="btn btn-danger btn-block mt-4">Logout</button>
            </form>
        </div>
    </div>
    <div class="main-content">
        <div id="searchResults">
            <!-- Hasil pencarian akan dimuat di sini -->
        </div>
        <div id="defaultContent">
            <?php while ($row = $result_foto->fetch_assoc()):
                $sql_count_like = "SELECT COUNT(*) AS like_count FROM likefoto WHERE fotoid = ?";
                $stmt_count_like = $conn->prepare($sql_count_like);
                $stmt_count_like->bind_param("i", $row['fotoid']);
                $stmt_count_like->execute();
                $result_like = $stmt_count_like->get_result();
                $like_data = $result_like->fetch_assoc();
                $like_count = $like_data['like_count'];
                $stmt_count_like->close();



                // Query untuk menghitung like (yang sudah ada)
                $sql_count_like = "SELECT COUNT(*) AS like_count FROM likefoto WHERE fotoid = ?";
                $stmt_count_like = $conn->prepare($sql_count_like);
                $stmt_count_like->bind_param("i", $row['fotoid']);
                $stmt_count_like->execute();
                $result_like = $stmt_count_like->get_result();
                $like_data = $result_like->fetch_assoc();
                $like_count = $like_data['like_count'];
                $stmt_count_like->close();

                // Query baru untuk menghitung komentar
                $sql_count_comment = "SELECT COUNT(*) AS comment_count FROM komentarfoto WHERE fotoid = ?";
                $stmt_count_comment = $conn->prepare($sql_count_comment);
                $stmt_count_comment->bind_param("i", $row['fotoid']);
                $stmt_count_comment->execute();
                $result_comment = $stmt_count_comment->get_result();
                $comment_data = $result_comment->fetch_assoc();
                $comment_count = $comment_data['comment_count'];
                $stmt_count_comment->close();
            ?>

                <div class="post card mb-2 ">
                    <div class="card-header d-flex align-items-center">
                        <img src="<?php echo $row['profile_picture']; ?>" alt="User Avatar" class="rounded-circle" width="50" height="50">
                        <div class="ml-3">
                            <h4><?php echo $row['namalengkap']; ?></h4>
                            <p class="text-muted"><?php echo formatTimeAgo($row['tanggalunggah']); ?></p>
                        </div>
                    </div>
                    <div class="card-body">
                        <img src="<?php echo $row['lokasifile']; ?>" class="img-fluid mb-2" style="width: 100%; height: auto; max-height:  ;  object-fit: cover;" alt="Post Image">
                        <h5><?php echo $row['judulfoto']; ?></h5>
                        <p><?php echo $row['deskripsifoto']; ?></p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-primary like-btn" data-fotoid="<?php echo $row['fotoid']; ?>">
                            Like <span class="like-count"><?php echo $like_count; ?></span>
                        </button>
                        <!-- Modifikasi tombol komentar pada card post -->
                        <!-- Modifikasi tombol komentar pada card post -->
                        <button class="btn btn-outline-secondary comment-btn"
                            data-fotoid="<?php echo $row['fotoid']; ?>"
                            data-image="<?php echo $row['lokasifile']; ?>"
                            data-namalengkap="<?php echo $row['namalengkap']; ?>"
                            data-userimage="<?php echo $row['profile_picture']; ?>"
                            data-uploaddate="<?php echo formatTimeAgo($row['tanggalunggah']); ?>">
                            Komentar <span class="comment-count"><?php echo $comment_count; ?></span>
                        </button>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>

    <!-- Tambahkan modal di bagian bawah body sebelum script -->
    <div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <button type="button" class="close position-absolute" style="right: 10px; top: 10px; z-index: 1;" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row no-gutters">
                        <!-- Gambar di sisi kiri -->
                        <div class="col-md-7">
                            <div class="h-100 d-flex align-items-center justify-content-center bg-light">
                                <img src="" id="modalImage" class="img-fluid" style="max-height: 80vh; object-fit: contain;">
                            </div>
                        </div>
                        <!-- Konten di sisi kanan -->
                        <div class="col-md-5">
                            <div class="d-flex flex-column h-100">
                                <!-- Header - Profil user -->
                                <div class="p-3 border-bottom">
                                    <div class="d-flex align-items-center">
                                        <img src="" id="modalUserImage" class="rounded-circle mr-3" width="40" height="40">
                                        <div>
                                            <h6 class="mb-0" id="modalUsername"></h6>
                                            <small class="text-muted" id="modalUploadDate"></small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bagian tengah - Daftar komentar -->
                                <div class="comments-section flex-grow-1 overflow-auto p-3" style="max-height: 50vh;" id="commentsList">
                                    <!-- Komentar akan dimuat di sini -->
                                </div>

                                <!-- Footer - Form komentar -->
                                <div class="p-3 border-top">
                                    <form id="commentForm" class="mb-0">
                                        <input type="hidden" id="modalFotoId" name="fotoid">
                                        <div class="input-group">
                                            <textarea class="form-control" id="commentText" name="comment" placeholder="Tambahkan komentar..." rows="2"></textarea>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="submit">Kirim</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="right-column">
        <div class="user-profile mb-4">
            <img src="<?php echo $user['profile_picture']; ?>" alt="User Avatar" class="rounded-circle mr-3" width="50" height="50">
            <h4><?php echo $user['namalengkap']; ?></h4>
            <p>@<?php echo $user['username']; ?></p>
            <a href="edit_profile.php" class="btn btn-outline-primary">Edit Profil</a>
        </div>
        <div class="other-users">
            <h5>Pengguna Lain:</h5>
            <ul class="list-unstyled">
                <?php
                $sql_other_users = "SELECT * FROM user WHERE userid != ?";
                $stmt_other_users = $conn->prepare($sql_other_users);
                $stmt_other_users->bind_param("i", $userid);
                $stmt_other_users->execute();
                $result_other_users = $stmt_other_users->get_result();

                while ($other_user = $result_other_users->fetch_assoc()):
                ?>
                    <li class="media mb-2">
                        <img src="<?php echo $other_user['profile_picture']; ?>" alt="User Avatar" class="rounded-circle mr-3" width="40" height="40">
                        <div class="media-body">
                            <h6 class="mt-0 mb-1"><?php echo $other_user['namalengkap']; ?></h6>
                        </div>
                    </li>
                <?php endwhile; ?>
                <?php $stmt_other_users->close(); ?>
            </ul>
        </div>
    </div>



    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">My Gallery</h4>
            <div class="search-icon">
                <i class="fas fa-search"></i>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="mobile-nav">
        <div class="nav-items">
            <a href="index.php" class="nav-item">
                <i class="fas fa-home"></i>
            </a>
            <a href="album.php" class="nav-item">
                <i class="fas fa-images"></i>
            </a>
            <a href="foto.php" class="nav-item">
                <i class="fas fa-camera"></i>
            </a>
            <a href="#" class="nav-item profile-trigger">
                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile" class="rounded-circle" width="24" height="24">
            </a>
        </div>
    </div>

    <!-- Profile Modal -->
    <div class="profile-modal">
        <div class="user-profile mb-4">
            <div class="d-flex align-items-center mb-3">
                <img src="<?php echo $user['profile_picture']; ?>" alt="User Avatar" class="rounded-circle mr-3" width="50" height="50">
                <div>
                    <h5 class="mb-0"><?php echo $user['namalengkap']; ?></h5>
                    <p class="text-muted mb-0 d-flex">@<?php echo $user['username']; ?></p>
                </div>
            </div>
            <a href="edit_profile.php" class="btn btn-outline-primary btn-sm btn-block">Edit Profil</a>
            <form action="logout.php" method="post" class="mt-2">
                <button type="submit" class="btn btn-danger btn-sm btn-block">Logout</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            function updateLikeButton(button, isLiked) {
                if (isLiked) {
                    button.addClass('liked');
                    button.attr('data-liked', 'true');
                } else {
                    button.removeClass('liked');
                    button.attr('data-liked', 'false');
                }
            }

            $(".like-btn").click(function() {
                var button = $(this);
                var fotoid = button.data("fotoid");
                var isLiked = button.attr('data-liked') === 'true';

                $.ajax({
                    url: "like.php",
                    type: "POST",
                    data: {
                        fotoid: fotoid
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "liked") {
                            var likeCount = parseInt(button.find(".like-count").text());
                            button.find(".like-count").text(likeCount + 1);
                            updateLikeButton(button, true);
                        } else if (response.status === "unliked") {
                            var likeCount = parseInt(button.find(".like-count").text());
                            button.find(".like-count").text(likeCount - 1);
                            updateLikeButton(button, false);
                        }
                    }
                });
            });;

            // Fungsi untuk memuat komentar
            function loadComments(fotoid) {
                $.ajax({
                    url: 'get_comments.php',
                    type: 'GET',
                    data: {
                        fotoid: fotoid
                    },
                    success: function(response) {
                        $('#commentsList').html(response);
                    }
                });
            }


            // Handler untuk tombol komentar
            $('.comment-btn').click(function() {
                const fotoid = $(this).data('fotoid');
                const image = $(this).data('image');
                const username = $(this).data('namalengkap');
                const userimage = $(this).data('userimage');
                const uploaddate = $(this).data('uploaddate');


                $('#modalImage').attr('src', image);
                $('#modalUserImage').attr('src', userimage);
                $('#modalUsername').text(username);
                $('#modalUploadDate').text(uploaddate);
                $('#modalFotoId').val(fotoid);

                loadComments(fotoid);
                $('#commentModal').modal('show');
            });

            // Handler untuk form komentar
            $('#commentForm').submit(function(e) {
                e.preventDefault();
                const fotoid = $('#modalFotoId').val();
                const comment = $('#commentText').val();



                if (comment === "") {
                    alert("Komentar tidak boleh kosong!"); // Tampilkan peringatan
                    return; // Hentikan eksekusi jika kosong
                }
                $.ajax({
                    url: 'add_comment.php',
                    type: 'POST',
                    data: {
                        fotoid: fotoid,
                        comment: comment
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#commentText').val('');
                            loadComments(fotoid);
                        } else {
                            alert(response.message); // Tampilkan pesan kesalahan
                        }
                    }
                });
            });


            const searchForm = $('#searchForm');
            const searchInput = $('#searchInput');
            const defaultContent = $('#defaultContent');
            const searchResults = $('#searchResults');

            // Sembunyikan hasil pencarian awalnya
            searchResults.hide();

            // Handle form submission
            searchForm.on('submit', function(e) {
                e.preventDefault();
                performSearch();
            });

            // Handle tombol enter
            searchInput.on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    performSearch();
                }
            });

            function performSearch() {
                const searchTerm = searchInput.val().trim();

                if (searchTerm === '') {
                    searchResults.hide();
                    defaultContent.show();
                    return;
                }

                // Tambahkan loading indicator
                searchResults.html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
                searchResults.show();
                defaultContent.hide();

                $.ajax({
                    url: 'search.php',
                    type: 'GET',
                    data: {
                        q: searchTerm
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.html) {
                            if (response.html.trim() === '') {
                                searchResults.html('<div class="alert alert-info">Tidak ada hasil ditemukan</div>');
                            } else {
                                searchResults.html(response.html);
                                // Reinitialize like and comment buttons for search results
                                initializeLikeButtons();
                                initializeCommentButtons();
                            }
                        } else {
                            searchResults.html('<div class="alert alert-info">Tidak ada hasil ditemukan</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        searchResults.html('<div class="alert alert-danger">Terjadi kesalahan saat melakukan pencarian: ' + error + '</div>');
                        console.error('Search error:', error);
                    }
                });
            }

            // Clear search
            searchInput.on('input', function() {
                if ($(this).val() === '') {
                    searchResults.hide();
                    defaultContent.show();
                }
            });

            // Function to initialize like buttons
            function initializeLikeButtons() {
                $('.like-btn').off('click').on('click', function() {
                    var button = $(this);
                    var fotoid = button.data('fotoid');

                    $.ajax({
                        url: 'like.php',
                        type: 'POST',
                        data: {
                            fotoid: fotoid
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'liked') {
                                var likeCount = parseInt(button.find('.like-count').text());
                                button.find('.like-count').text(likeCount + 1);
                            } else if (response.status === 'unliked') {
                                var likeCount = parseInt(button.find('.like-count').text());
                                button.find('.like-count').text(likeCount - 1);
                            }
                        }
                    });
                });
            }

            // Function to initialize comment buttons
            function initializeCommentButtons() {
                $('.comment-btn').off('click').on('click', function() {
                    const fotoid = $(this).data('fotoid');
                    const image = $(this).data('image');
                    const username = $(this).data('namalengkap');
                    const userimage = $(this).data('userimage');
                    const uploaddate = $(this).data('uploaddate');

                    $('#modalImage').attr('src', image);
                    $('#modalUserImage').attr('src', userimage);
                    $('#modalUsername').text(username);
                    $('#modalUploadDate').text(uploaddate);
                    $('#modalFotoId').val(fotoid);

                    loadComments(fotoid);
                    $('#commentModal').modal('show');
                });
            }

            // Profile Modal Toggle
            $('.profile-trigger').click(function(e) {
                e.preventDefault();
                $('.profile-modal').toggleClass('show');
            });

            // Close modal when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.profile-modal, .profile-trigger').length) {
                    $('.profile-modal').removeClass('show');
                }
            });

            // Mobile Search Toggle
            $('.search-icon').click(function() {
                // Here you can implement search functionality
                // For example, show a search overlay or redirect to search page
            });

        });

        // Add animation classes to elements as they appear in viewport
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.post.card');
            elements.forEach(element => {
                if (element.getBoundingClientRect().top < window.innerHeight) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        // Initialize animations
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('scroll', animateOnScroll);
            animateOnScroll();
        });
    </script>
    <div class="copyright">
        <span>© 2024 SMK NEGERI 9 MEDAN. SULAIMAN AR</span>
    </div>
</body>

</html>

<?php $conn->close(); ?>