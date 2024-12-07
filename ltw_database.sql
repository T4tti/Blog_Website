-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 07, 2024 lúc 10:34 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ltw_database`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baiviet`
--

CREATE TABLE `baiviet` (
  `posts_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `baiviet_tags`
--

CREATE TABLE `baiviet_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `maxacnhan`
--

CREATE TABLE `maxacnhan` (
  `id` int(11) NOT NULL,
  `macode` varchar(6) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `maxacnhan`
--

INSERT INTO `maxacnhan` (`id`, `macode`, `email`) VALUES
(1, '9aedea', 'tai.lenguyenthanh14902@gmail.com'),
(2, 'a09fe6', '22022001@st.vlute.edu.vn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quyen`
--

CREATE TABLE `quyen` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(20) DEFAULT NULL,
  `mota` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `quyen`
--

INSERT INTO `quyen` (`role_id`, `role_name`, `mota`) VALUES
(0, 'user', NULL),
(1, 'admin', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `birth` date DEFAULT NULL,
  `gender` varchar(5) DEFAULT NULL,
  `role` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`id`, `username`, `fullname`, `email`, `password`, `birth`, `gender`, `role`, `created_at`) VALUES
(1, 'thtai5904', 'Lê Nguyễn Thành Tài', 'tai.lenguyenthanh14902@gmail.com', '$2y$10$MU4m.VxQw8d9uEo6kF1oweRHw/EMA1SSZbzjvjY7VWK60J4AzjWiu', NULL, NULL, 0, '2024-12-07 07:32:35'),
(2, 'vana04', 'Nguyễn Văn A', '22022001@st.vlute.edu.vn', '$2y$10$zOBbwjGV.e8oKGVKCeJj4Ogq6UrPDO7Ju6NS3z0nkJLIhQ03ftp2.', NULL, NULL, 0, '2024-12-07 08:28:21'),
(3, 'vanb2000', 'Nguyễn Văn B', 'tehobe1809@pokeline.com', '$2y$10$g4Uy7nNG6xLHlWg4cYrPYOJIw1ayJ64vCEgn68FcljRJ3sAZeiYCa', NULL, NULL, 0, '2024-12-07 09:21:41');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  ADD PRIMARY KEY (`posts_id`),
  ADD KEY `fk_baiviet_taikhoan` (`user_id`);

--
-- Chỉ mục cho bảng `baiviet_tags`
--
ALTER TABLE `baiviet_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `fk_baiviet_tags_tags` (`tag_id`);

--
-- Chỉ mục cho bảng `maxacnhan`
--
ALTER TABLE `maxacnhan`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `quyen`
--
ALTER TABLE `quyen`
  ADD PRIMARY KEY (`role_id`);

--
-- Chỉ mục cho bảng `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `emaiL` (`email`),
  ADD KEY `fk_taikhoan_quyen` (`role`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  MODIFY `posts_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `maxacnhan`
--
ALTER TABLE `maxacnhan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `baiviet`
--
ALTER TABLE `baiviet`
  ADD CONSTRAINT `fk_baiviet_taikhoan` FOREIGN KEY (`user_id`) REFERENCES `taikhoan` (`id`);

--
-- Các ràng buộc cho bảng `baiviet_tags`
--
ALTER TABLE `baiviet_tags`
  ADD CONSTRAINT `fk_baiviet_tags_baiviet` FOREIGN KEY (`post_id`) REFERENCES `baiviet` (`posts_id`),
  ADD CONSTRAINT `fk_baiviet_tags_tags` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`);

--
-- Các ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `fk_taikhoan_quyen` FOREIGN KEY (`role`) REFERENCES `quyen` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
