-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-04-30 08:38:00
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
-- 資料表結構 `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `postId` int(11) NOT NULL,
  `usersId` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `comments`
--

INSERT INTO `comments` (`comment_id`, `postId`, `usersId`, `comment_content`, `comment_date`) VALUES
(1, 6, 2, 'NOOOOO', '2023-04-22 12:12:09'),
(2, 7, 2, 'NOOO', '2023-04-22 12:14:48'),
(3, 8, 2, 'YES', '2023-04-22 12:15:26'),
(7, 6, 2, '哈摟', '2023-04-28 15:51:32'),
(9, 6, 2, '嗨嗨', '2023-04-28 15:51:48');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `postId` (`postId`),
  ADD KEY `usersId` (`usersId`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `posts` (`postId`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`usersId`) REFERENCES `users` (`usersId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
