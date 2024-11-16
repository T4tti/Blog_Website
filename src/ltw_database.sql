-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 16, 2024 lúc 04:46 AM
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
-- Cấu trúc bảng cho bảng `bai_viet`
--

CREATE TABLE `bai_viet` (
  `ID` int(11) NOT NULL,
  `ID_USER` int(11) NOT NULL,
  `ID_CHU_DE` int(11) NOT NULL,
  `TIEU_DE` varchar(255) NOT NULL,
  `NOI_DUNG` mediumtext NOT NULL,
  `NGAY_TAO` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bai_viet`
--

INSERT INTO `bai_viet` (`ID`, `ID_USER`, `ID_CHU_DE`, `TIEU_DE`, `NOI_DUNG`, `NGAY_TAO`) VALUES
(1, 1, 1, 'Hướng dẫn cơ bản về Python', 'Python là một ngôn ngữ lập trình phổ biến. Bài viết này sẽ hướng dẫn cách cài đặt và viết chương trình đầu tiên.', '2023-02-17 10:00:00'),
(2, 2, 2, 'Xây dựng trang web với HTML và CSS', 'Học cách tạo một trang web cơ bản sử dụng HTML và CSS trong vòng 30 phút.', '2024-05-16 11:00:00'),
(3, 3, 3, 'Làm quen với JavaScript', 'JavaScript là ngôn ngữ lập trình phổ biến trên web. Bài viết này sẽ giúp bạn bắt đầu.', '2022-04-20 12:00:00'),
(4, 2, 4, 'Tìm hiểu Docker và Kubernetes', 'Bài viết này giải thích cách sử dụng Docker và Kubernetes để triển khai ứng dụng.', '2024-03-16 13:00:00'),
(5, 3, 5, 'Phát triển ứng dụng Flutter', 'Học cách xây dựng ứng dụng di động đa nền tảng với Flutter.', '2024-11-16 14:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chu_de`
--

CREATE TABLE `chu_de` (
  `ID` int(11) NOT NULL,
  `TEN_CHU_DE` varchar(100) NOT NULL,
  `MO_TA` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chu_de`
--

INSERT INTO `chu_de` (`ID`, `TEN_CHU_DE`, `MO_TA`) VALUES
(1, 'Python & AI', 'Chủ đề về lập trình Python và Trí tuệ nhân tạo.'),
(2, 'Web Development', 'Phát triển các ứng dụng web.'),
(3, 'Front-end', 'Công nghệ giao diện người dùng.'),
(4, 'Cloud Computing', 'Điện toán đám mây và triển khai ứng dụng.'),
(5, 'Mobile Development', 'Phát triển ứng dụng di động.');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tai_khoan`
--

CREATE TABLE `tai_khoan` (
  `ID` int(11) NOT NULL,
  `TEN_USER` varchar(50) NOT NULL,
  `TEN_TK` varchar(20) NOT NULL,
  `PASS_TK` varchar(225) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `TG_TAO` datetime DEFAULT current_timestamp(),
  `ID_VT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tai_khoan`
--

INSERT INTO `tai_khoan` (`ID`, `TEN_USER`, `TEN_TK`, `PASS_TK`, `EMAIL`, `TG_TAO`, `ID_VT`) VALUES
(1, 'Nguyễn Văn A', 'admin', 'admin_1', 'admin@example.com', '2024-01-01 08:00:00', 0),
(2, 'Trần Thị B', 'tranthib', 'password_hash_2', 'tranthib@example.com', '2024-02-01 09:00:00', 1),
(3, 'Lê Văn C', 'levanc', 'password_hash_3', 'levanc@example.com', '2024-03-01 10:00:00', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vai_tro`
--

CREATE TABLE `vai_tro` (
  `id` int(11) NOT NULL,
  `TEN_VT` varchar(20) NOT NULL,
  `MOTA` mediumtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vai_tro`
--

INSERT INTO `vai_tro` (`id`, `TEN_VT`, `MOTA`) VALUES
(0, 'Admin', 'Quản trị viên hệ thống, có quyền cao nhất.'),
(1, 'User', 'Người dùng bình thường.');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_USER` (`ID_USER`),
  ADD KEY `ID_CHU_DE` (`ID_CHU_DE`);

--
-- Chỉ mục cho bảng `chu_de`
--
ALTER TABLE `chu_de`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `TEN_CHU_DE` (`TEN_CHU_DE`);

--
-- Chỉ mục cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `TEN_TK` (`TEN_TK`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`),
  ADD KEY `fk_id_vt` (`ID_VT`);

--
-- Chỉ mục cho bảng `vai_tro`
--
ALTER TABLE `vai_tro`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `TEN_VT` (`TEN_VT`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `bai_viet`
--
ALTER TABLE `bai_viet`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `chu_de`
--
ALTER TABLE `chu_de`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bai_viet`
--
ALTER TABLE `bai_viet`
  ADD CONSTRAINT `bai_viet_ibfk_1` FOREIGN KEY (`ID_USER`) REFERENCES `tai_khoan` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `bai_viet_ibfk_2` FOREIGN KEY (`ID_CHU_DE`) REFERENCES `chu_de` (`ID`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tai_khoan`
--
ALTER TABLE `tai_khoan`
  ADD CONSTRAINT `fk_id_vt` FOREIGN KEY (`ID_VT`) REFERENCES `vai_tro` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
