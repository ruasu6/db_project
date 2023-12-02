-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-04-30 07:25:52
-- 伺服器版本： 10.4.25-MariaDB
-- PHP 版本： 8.1.10

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
-- 資料表結構 `posts`
--

CREATE TABLE `posts` (
  `postId` int(11) NOT NULL,
  `usersId` int(11) NOT NULL,
  `usersName` varchar(20) NOT NULL,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `posts`
--

INSERT INTO `posts` (`postId`, `usersId`, `usersName`, `post_content`, `post_date`, `likes`) VALUES
(6, 2, 'Cai', 'A', '0000-00-00 00:00:00', 1),
(7, 2, 'Cai', 'ACB', '0000-00-00 00:00:00', 1),
(8, 2, 'Cai', 'ACBAA', '2023-04-22 23:26:50', 1),
(9, 2, 'Cai', 'AAA', '2023-04-22 23:26:54', 1),
(10, 2, 'Cai', 'AAA', '2023-04-22 23:52:15', 1),
(26, 2, 'Cai', 'Z', '2023-04-24 01:02:53', 1),
(32, 2, 'Cai', '哈摟', '2023-04-29 14:12:24', 0),
(47, 2, 'Cai', '123', '2023-04-29 14:25:52', 1),
(55, 2, 'Cai', 'A', '2023-04-29 22:19:59', 1);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`postId`),
  ADD KEY `usersId` (`usersId`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `posts`
--
ALTER TABLE `posts`
  MODIFY `postId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`usersId`) REFERENCES `users` (`usersId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
