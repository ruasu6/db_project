-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2023-04-30 08:38:11
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
-- 資料表結構 `likes`
--

CREATE TABLE `likes` (
  `usersId` int(11) NOT NULL,
  `postId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 傾印資料表的資料 `likes`
--

INSERT INTO `likes` (`usersId`, `postId`) VALUES
(2, 6),
(2, 8),
(2, 9),
(2, 10),
(2, 26),
(2, 32),
(2, 47),
(2, 55);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `likes`
--
ALTER TABLE `likes`
  ADD UNIQUE KEY `user_id` (`usersId`,`postId`),
  ADD KEY `posts` (`postId`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_users` FOREIGN KEY (`usersId`) REFERENCES `users` (`usersId`),
  ADD CONSTRAINT `posts` FOREIGN KEY (`postId`) REFERENCES `posts` (`postId`),
  ADD CONSTRAINT `users` FOREIGN KEY (`usersId`) REFERENCES `users` (`usersId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
