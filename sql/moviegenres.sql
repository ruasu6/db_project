-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-05-13 08:56:22
-- 伺服器版本： 10.4.27-MariaDB
-- PHP 版本： 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `video_db`
--

-- --------------------------------------------------------

--
-- 資料表結構 `moviegenres`
--

CREATE TABLE `moviegenres` (
  `mId` int(100) NOT NULL,
  `mName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `moviegenres`
--

INSERT INTO `moviegenres` (`mId`, `mName`) VALUES
(12, 'Adventure'),
(14, 'Fantasy'),
(16, 'Animation'),
(18, 'Drama'),
(27, 'Horror'),
(28, 'Action'),
(35, 'Comedy'),
(36, 'History'),
(37, 'Western'),
(53, 'Thriller'),
(80, 'Crime'),
(99, 'Documentary'),
(878, 'Science Fiction'),
(9648, 'Mystery'),
(10402, 'Music'),
(10749, 'Romance'),
(10751, 'Family'),
(10752, 'War'),
(10770, 'TV Movie');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `moviegenres`
--
ALTER TABLE `moviegenres`
  ADD PRIMARY KEY (`mId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
