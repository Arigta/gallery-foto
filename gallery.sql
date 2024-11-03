-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Nov 2024 pada 16.26
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gallery`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `album`
--

CREATE TABLE `album` (
  `albumid` int(11) NOT NULL,
  `namaalbum` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggaldibuat` date NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `album`
--

INSERT INTO `album` (`albumid`, `namaalbum`, `deskripsi`, `tanggaldibuat`, `userid`) VALUES
(2, 'Manga', 'berisi post seputar manga\r\n', '2024-10-29', 7),
(7, 'Anime', 'Isi seputar random anime aja', '2024-10-31', 7),
(8, 'Meme', 'isi ny garing semua', '2024-10-31', 7),
(9, 'profile', '.\r\n', '2024-10-31', 9),
(10, 'Wallpapers', 'buat desktop', '2024-10-31', 10),
(11, 'Manga', 'koleksi panel manga ', '2024-10-31', 11),
(12, 'Anime', 'saya suka anime\r\n', '2024-10-31', 12),
(14, 'Wallpapers', 'Berisi wallpater hd', '2024-10-31', 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `foto`
--

CREATE TABLE `foto` (
  `fotoid` int(11) NOT NULL,
  `judulfoto` varchar(255) NOT NULL,
  `deskripsifoto` text NOT NULL,
  `tanggalunggah` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `lokasifile` varchar(255) NOT NULL,
  `albumid` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `foto`
--

INSERT INTO `foto` (`fotoid`, `judulfoto`, `deskripsifoto`, `tanggalunggah`, `lokasifile`, `albumid`, `userid`) VALUES
(2, 'Heroine datang!!11', 'SC: Tonari no Seki no Yankee Shimizu-san\r\n\r\njadi gini.. ad heroin yang tampilan ny seperti berandalan. dia ingin mengubah penampilan nya menjadi type ceww idaman mc. begitulah\r\n\r\n', '2024-10-30 18:13:22', 'uploads/WhatsApp Image 2024-10-29 at 09.51.54_11154434.jpg', 2, 7),
(12, 'Bit karbit', 'SC: Roshi Dere', '2024-11-01 16:10:29', 'uploads/6722ed7f2b6d4_1695551162-27019_waifu2x_Anime_4.0x_noise2_GPU.jpg', 7, 7),
(13, 'sare~', 'malas berad', '2024-10-31 02:42:56', 'uploads/6722eeb0dda4f_Screenshot 2024-10-30 095924.png', 8, 7),
(14, 'manga apa ini?', 'infokan saus!!', '2024-10-31 02:47:46', 'uploads/6722efd2501b9_457569361_1046656776915975_2537967170235086910_n.jpg', 2, 7),
(15, 'For Real??', 'susah nyari mie nya ', '2024-10-31 02:50:11', 'uploads/6722f0638a8b2_464567023_1103435544500286_6257337197132523078_n.jpg', 8, 7),
(16, 'isekai route?', 'ga tau judul ny', '2024-10-30 02:52:35', 'uploads/6722f0f39534c_465013160_555855337184880_2156955207067565380_n.jpg', 2, 7),
(17, 'untuk nyata', 'sare~', '2024-10-31 02:53:44', 'uploads/6722f138014e5_464944181_947859773894266_8046410516325233846_n.jpg', 8, 7),
(18, 'logo linux', 'ayo belajar linux. seru loh', '2024-10-31 03:06:26', 'uploads/6722f432027dc_Arch.jpg', 9, 9),
(19, 'Night View', 'The stars shinig bright.', '2024-10-31 03:10:37', 'uploads/6722f52d5267e_123703982_p0.png', 10, 10),
(20, 'Raja Garam - Dewa Pemarah', 'Imut bjir 😋\r\n', '2024-10-31 03:18:30', 'uploads/6722f706c01be_WhatsApp Image 2024-10-31 at 10.13.30_13a596b0.jpg', 11, 11),
(21, 'Violet Evergarden Episode 10', 'Ayo nonton anime ini. sangat seru dan bikin hati tenang. btw. Violet udh jadi istri saya', '2024-10-31 03:34:04', 'uploads/6722faac4095e_Violet Evergarden Episode 10 Discussion.jpeg', 12, 12),
(22, 'night wallpaper', 'Untuk dekstop', '2024-11-03 14:03:29', 'uploads/672311252ac03_wp2.jpg', 14, 13),
(23, 'Dekstop wallpaper', 'kayak kenal ya...', '2024-11-03 14:12:05', 'uploads/672784b5418e0_1725771263015.jpg', 14, 13);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komentarfoto`
--

CREATE TABLE `komentarfoto` (
  `komentarid` int(11) NOT NULL,
  `fotoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `isikomentar` text NOT NULL,
  `tanggalkomentar` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komentarfoto`
--

INSERT INTO `komentarfoto` (`komentarid`, `fotoid`, `userid`, `isikomentar`, `tanggalkomentar`) VALUES
(60, 2, 7, 'jadi kalo semisal kita edit postingan makan like dan komen ny akan di riset\n', '2024-10-30 17:47:27'),
(64, 2, 7, 'ss', '2024-10-30 18:05:54'),
(65, 2, 7, 'tes\n', '2024-10-30 18:05:59'),
(66, 2, 7, 'sss', '2024-10-30 18:09:12'),
(67, 2, 7, 'sssi', '2024-10-30 18:09:31'),
(68, 2, 7, 'tes\'\n\n', '2024-10-30 18:09:38'),
(69, 2, 7, 'in tes', '2024-10-30 19:14:09'),
(70, 12, 7, 'punya gw!!\n', '2024-10-31 03:39:49'),
(71, 17, 9, 'for real!!\n\n', '2024-10-31 04:01:41'),
(72, 16, 9, 'bjrer\n', '2024-10-31 04:02:00'),
(73, 13, 9, 'turu lek!', '2024-10-31 04:02:27'),
(74, 12, 9, 'bacot! itu punya gw !', '2024-10-31 04:02:57'),
(75, 2, 9, 'gede banget.. gambar ny\n', '2024-10-31 04:03:23'),
(76, 18, 10, 'gg nopal\n', '2024-10-31 04:10:48'),
(77, 19, 10, 'sc ny dari pixiv\n', '2024-10-31 04:11:12'),
(78, 19, 11, 'mana!! spill kirim pribadi bg!!', '2024-10-31 04:13:09'),
(79, 16, 11, 'info judul', '2024-10-31 04:14:01'),
(80, 12, 11, 'apalah karbit', '2024-11-01 23:10:12'),
(81, 2, 11, 'beliau ini..', '2024-10-31 04:14:55'),
(82, 20, 11, '😍😍😍', '2024-10-31 04:19:22'),
(83, 20, 9, 'judul man\n', '2024-10-31 04:20:05'),
(84, 15, 9, 'rill\n', '2024-10-31 04:21:10'),
(85, 14, 9, 'tanya arman', '2024-10-31 04:21:27'),
(86, 20, 10, 'UWOHH! KOHARU!!!🥰🥰🥰', '2024-10-31 04:22:43'),
(87, 16, 10, 'bisa gitu\n', '2024-10-31 04:23:22'),
(88, 12, 10, 'UWOOHH!! ALYA CHAN!!!', '2024-10-31 04:23:57'),
(89, 2, 10, 'BESAR!!', '2024-10-31 04:24:25'),
(90, 18, 12, 'nopal nolan ', '2024-10-31 04:30:32'),
(91, 12, 12, 'mendig violet\n', '2024-10-31 04:30:54'),
(92, 2, 12, 'besar banget ya..\n', '2024-10-31 04:31:17'),
(93, 21, 10, 'makasi rekomen animenya bg. tenang bg, aku lebih suka anime girl white hair.', '2024-10-31 04:35:49'),
(94, 21, 9, '🗿', '2024-10-31 04:37:43'),
(95, 21, 11, 'waw episode 10 💀💀', '2024-10-31 04:38:57'),
(96, 14, 11, 'ga tau.', '2024-10-31 04:39:34'),
(97, 21, 7, 'gws. btw eps 10...\n', '2024-10-31 05:04:18'),
(98, 18, 7, 'apaan nih\n', '2024-10-31 05:04:47'),
(99, 21, 7, 'YTTA', '2024-10-31 05:13:54'),
(100, 22, 13, 'bagus\n', '2024-10-31 06:10:22'),
(101, 22, 13, 'bagus', '2024-10-31 06:10:27'),
(102, 13, 7, 'tes komen\n', '2024-11-03 13:59:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likefoto`
--

CREATE TABLE `likefoto` (
  `likeid` int(11) NOT NULL,
  `fotoid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `tanggallike` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likefoto`
--

INSERT INTO `likefoto` (`likeid`, `fotoid`, `userid`, `tanggallike`) VALUES
(47, 12, 7, '2024-10-31 02:39:38'),
(48, 17, 9, '2024-10-31 03:01:31'),
(49, 16, 9, '2024-10-31 03:01:47'),
(50, 15, 9, '2024-10-31 03:02:04'),
(51, 13, 9, '2024-10-31 03:02:13'),
(52, 2, 9, '2024-10-31 03:03:26'),
(53, 12, 9, '2024-10-31 03:03:28'),
(54, 18, 10, '2024-10-31 03:10:51'),
(56, 18, 11, '2024-10-31 03:13:14'),
(57, 17, 11, '2024-10-31 03:13:19'),
(58, 15, 11, '2024-10-31 03:14:05'),
(59, 16, 11, '2024-10-31 03:14:07'),
(60, 13, 11, '2024-10-31 03:14:22'),
(61, 12, 11, '2024-10-31 03:15:02'),
(62, 2, 11, '2024-10-31 03:15:04'),
(63, 20, 11, '2024-10-31 03:19:12'),
(73, 18, 9, '2024-10-31 03:20:46'),
(74, 14, 9, '2024-10-31 03:21:30'),
(76, 16, 10, '2024-10-31 03:23:29'),
(78, 18, 12, '2024-10-31 03:30:07'),
(79, 17, 12, '2024-10-31 03:30:36'),
(80, 2, 12, '2024-10-31 03:31:22'),
(81, 21, 12, '2024-10-31 03:34:16'),
(82, 21, 10, '2024-10-31 03:34:49'),
(83, 20, 10, '2024-10-31 03:35:56'),
(84, 21, 9, '2024-10-31 03:37:47'),
(85, 20, 9, '2024-10-31 03:37:50'),
(86, 21, 11, '2024-10-31 03:38:07'),
(87, 21, 7, '2024-10-31 04:13:33'),
(90, 22, 13, '2024-10-31 05:10:16'),
(91, 22, 7, '2024-11-01 00:22:35'),
(94, 2, 7, '2024-11-01 03:46:10'),
(106, 22, 10, '2024-11-02 02:12:55'),
(108, 12, 10, '2024-11-02 02:18:47'),
(109, 19, 7, '2024-11-03 12:56:40'),
(111, 23, 13, '2024-11-03 14:21:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `namalengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `profile_picture` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userid`, `username`, `password`, `email`, `namalengkap`, `alamat`, `profile_picture`) VALUES
(6, 'user33', '$2y$10$jyIzsBTLEeAAN5QkhbZwx.rjjjMQvwoDEqrpx2qN47THoTLbcTzdy', 'neko@gmail.com', 'user', 'alamat', 'uploads/6721c81284935.png'),
(7, 'pengguna1', '$2y$10$134FfVPJbcAXKjNGtRI1Megi//77H93lu62fGXvLUPTWkCy5jAp5a', 'user@ha', 'Sulaiman Agen Rahasia', 'Jl Taniasli Komplek Green Harmoni Residence. Blok B.16', 'img/profile/profile_7_1730645866.png'),
(9, 'user3', '$2y$10$pq0qZeKLr4Xt6PWR0fXRxuHKxe4ghs9ehhUpe/hcK6A6polug.O/m', 'shiroNeko@gmail', 'Nopal (mR.07)', 'Jl Taniasli Komplek Green Harmoni Residence. Blok B.16', 'img/profile/profile_9_1730343655.png'),
(10, 'user4', '$2y$10$O6Hm8lfCFYbnbVc8JOh6JeeVct104vOwHd6xAHAdaSOg2N3VxSTEG', 'neko@gmail', 'Reza mulia putra', 'pinang bares', 'img/profile/profile_10_1730344188.png'),
(11, 'user22', '$2y$10$b97sAV0cPJFSz4Xq3mO2tuRi06Gc9MbpfmAdiVnOMy/7AX8DN9LOq', 'shiroNeko@gmail', 'Arman Wijaya (Kamamachi)', 'Jl Taniasli Komplek Green Harmoni Residence. Blok B.16', 'img/profile/profile_11_1730344648.png'),
(12, 'user5', '$2y$10$6AXmGbOj/.dLTIVldM5aA.b8wVCklZoT4kZx2nKpr04hfi8QD0uc.', 'khoir@gmai', 'Aulia Khoir', 'Jl Taniasli Komplek Green Harmoni Residence. Blok B.16', 'img/profile/profile_12_1730345397.png'),
(13, 'user6', '$2y$10$0PCocbfKwRYWUtDBN/mbu.rUpbGrU0GHhlT81iDISprcthvmiyCaK', 'shiroNeko@gmail', 'Tes Pengguna', 'Jl Taniasli Komplek Green Harmoni Residence. Blok B.16', 'img/profile/profile_13_1730351461.png');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`albumid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `foto`
--
ALTER TABLE `foto`
  ADD PRIMARY KEY (`fotoid`),
  ADD KEY `albumid` (`albumid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `komentarfoto`
--
ALTER TABLE `komentarfoto`
  ADD PRIMARY KEY (`komentarid`),
  ADD KEY `fotoid` (`fotoid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  ADD PRIMARY KEY (`likeid`),
  ADD KEY `fotoid` (`fotoid`),
  ADD KEY `userid` (`userid`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `album`
--
ALTER TABLE `album`
  MODIFY `albumid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `foto`
--
ALTER TABLE `foto`
  MODIFY `fotoid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `komentarfoto`
--
ALTER TABLE `komentarfoto`
  MODIFY `komentarid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  MODIFY `likeid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `album_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `foto`
--
ALTER TABLE `foto`
  ADD CONSTRAINT `foto_ibfk_1` FOREIGN KEY (`albumid`) REFERENCES `album` (`albumid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foto_ibfk_2` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komentarfoto`
--
ALTER TABLE `komentarfoto`
  ADD CONSTRAINT `komentarfoto_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `komentarfoto_ibfk_2` FOREIGN KEY (`fotoid`) REFERENCES `foto` (`fotoid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `likefoto`
--
ALTER TABLE `likefoto`
  ADD CONSTRAINT `likefoto_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `likefoto_ibfk_2` FOREIGN KEY (`fotoid`) REFERENCES `foto` (`fotoid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
