<?php
session_start();
require_once 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

// Ambil data pengguna dari database
$userid = $_SESSION['userid'];
$sql = "SELECT * FROM user WHERE userid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.css">
    <link rel="icon" href="img/logo.png" type="image/png">
    <!-- Resolusi lebih tinggi untuk perangkat lain (opsional) -->
    <link rel="apple-touch-icon" sizes="180x180" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/logo.png">
    <style>
        
        #preview {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        img#profileImg {
            max-width: 100%;
        }

        .modal-dialog {
            max-width: 90%;
            margin: 1.75rem auto;
        }

        .img-container {
            max-height: 70vh;
            overflow: hidden;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="update_profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" class="form-control" name="namalengkap" value="<?php echo htmlspecialchars($user['namalengkap']); ?>">
            </div>
            <div class="form-group">
                <label>Foto Profil</label>
                <div id="preview">
                    <img id="profileImg" src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture">
                </div>
                <input type="file" class="form-control-file" id="profilePicture" name="profile_picture">
                <input type="hidden" id="croppedImage" name="cropped_image">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <!-- Modal untuk Cropper -->
    <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-labelledby="cropperModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropperModalLabel">Crop Foto Profil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="imageToCrop" src="" alt="Image to Crop">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="cropButton">Crop & Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.9/cropper.min.js"></script>
    <script>
        let cropper;
        const profilePictureInput = document.getElementById('profilePicture');
        const profileImg = document.getElementById('profileImg');
        const imageToCrop = document.getElementById('imageToCrop');
        const croppedImageInput = document.getElementById('croppedImage');


        profilePictureInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imageToCrop.src = e.target.result;
                    $('#cropperModal').modal('show');
                };
                reader.readAsDataURL(file);
            }
        });

        $('#cropperModal').on('shown.bs.modal', function() {
            cropper = new Cropper(imageToCrop, {
                aspectRatio: 1,
                viewMode: 2, // Set viewMode to 2 to fit the image within the container
                guides: true,
                autoCropArea: 1, // Set autoCropArea to 1 to automatically fit the image
                movable: true,
                zoomable: true,
                cropBoxMovable: true,
                cropBoxResizable: true
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        document.getElementById('cropButton').addEventListener('click', function() {
            const canvas = cropper.getCroppedCanvas();
            profileImg.src = canvas.toDataURL();
            croppedImageInput.value = canvas.toDataURL();
            $('#cropperModal').modal('hide');
        });
    </script>
</body>

</html>