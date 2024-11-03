Website Galeri Foto adalah platform berbasis web yang memungkinkan pengguna untuk membuat album, mengunggah foto, serta memberikan like dan komentar pada foto. Proyek ini dibuat sebagai bagian dari Uji Kompetensi Keahlian (UKK) untuk bidang pengembangan website.
Fitur

Autentikasi Pengguna: Registrasi, login, dan logout untuk mengamankan akses ke fitur.
Album dan Foto: Pengguna dapat membuat album, mengunggah foto, dan melihat daftar album pribadi.
Interaksi: Pengguna dapat memberi like dan komentar pada foto.
Pencarian: Fitur pencarian untuk menemukan foto atau pengguna tertentu.
Profil Pengguna: Fitur untuk mengedit profil pengguna, termasuk mengganti foto profil dan nama lengkap.

Teknologi yang Digunakan

Frontend: HTML, CSS (Bootstrap), JavaScript
Backend: PHP (dengan MySQL untuk database)
Database: MySQL
Server Lokal: XAMPP atau LAMP

Struktur Database

Database proyek ini bernama gallery, dengan tabel-tabel utama sebagai berikut:

user: Menyimpan informasi pengguna (userid, username, password, email, profile_picture).
album: Menyimpan data album (albumid, nama album, deskripsi, tanggal dibuat, userid).
foto: Menyimpan data foto (fotoid, judul, deskripsi, tanggal unggah, lokasifile, albumid, userid).
komentarfoto: Menyimpan komentar pada foto (komentarid, isi komentar, tanggal, fotoid, userid).
likefoto: Menyimpan informasi like pada foto (likeid, fotoid, userid).
