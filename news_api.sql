-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2025 at 01:27 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `news_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `category_id` int(11) NOT NULL,
  `image_path` mediumtext NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author`, `created_at`, `category_id`, `image_path`, `views`) VALUES
(21, 'newsportal', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperioresLorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores..', 'Mai', '2025-04-23 09:55:23', 3, '', 1),
(24, 'Surveying Engineer', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tempor magna eget elit efficitur, at accumsan sem placerat. Nulla tellus libero, mattis nec molestie at, facilisis ut turpis. Vestibulum dolor metus, tincidunt eget odioLorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tempor magna eget elit efficitur, at accumsan sem placerat. Nulla tellus libero, mattis nec molestie at, facilisis ut turpis. Vestibulum dolor metus, tincidunt eget odioLorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur tempor magna eget elit efficitur, at accumsan sem placerat. Nulla tellus libero, mattis nec molestie at, facilisis ut turpis. Vestibulum dolor metus, tincidunt eget odio', 'mmm', '2025-04-23 15:04:10', 1, '', 1),
(26, 'Data Specialist', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperioresLorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores.Lorem ipsum, dolor sit amet consectetur adipisicing elit. Et dolores odit rerum obcaecati ullam cumque maiores illum nobis laudantium asperiores..', 'john', '2025-04-23 20:23:36', 6, '', 6),
(27, 'Cats', 'Cats are one of the most popular and lovable pets in the world. They are small, furry animals known for their playful and curious nature. Cats have sharp claws and teeth, which they use for hunting and climbing. They are very clean animals and groom themselves by licking their fur.', 'Meow', '2025-04-25 22:58:54', 3, '', 4),
(28, 'Dogs', 'Dogs are sometimes referred to as man\'s best friend because they are kept as domestic pets and are usually loyal and like being around humans. They are also helpful in reducing stress, anxiety, and depression, loneliness, encourage exercise and playfulness and even improve your cardiovascular health.', 'Bark', '2025-04-25 23:00:35', 3, '', 21),
(41, 'Test ', 'Test Test Test ', 'Test ', '2025-04-26 13:46:25', 6, 'g2.jpg', 10),
(42, 'img-fluid mb-3', 'img-fluid mb-3', 'img-fluid mb-3', '2025-04-26 13:52:23', 6, 'g3.webp', 8),
(43, 'img-fluid mb-3', 'img-fluid mb-3img-fluid mb-3img-fluid mb-3img-fluid mb-3', 'img-fluid mb-3', '2025-04-26 13:54:24', 3, '', 20),
(44, 'a', 'b', 'c', '2025-04-28 16:52:16', 1, '', 94);

--
-- Triggers `articles`
--
DELIMITER $$
CREATE TRIGGER `after_article_insert` AFTER INSERT ON `articles` FOR EACH ROW BEGIN
    INSERT INTO notfications (user_id, notfication_description, article_id)
    SELECT 
        user_id,
        CONCAT(NEW.title, ' Written by: ', NEW.author),
        NEW.id
    FROM users;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `bookmark_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`bookmark_id`, `user_id`, `article_id`, `added_at`) VALUES
(1, 33, 27, '2025-04-25 23:24:03'),
(19, 7, 44, '2025-04-30 23:45:33'),
(20, 20, 44, '2025-05-01 22:01:41');

-- --------------------------------------------------------

--
-- Table structure for table `breaking_news`
--

CREATE TABLE `breaking_news` (
  `id` int(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `duration` int(255) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `breaking_news`
--

INSERT INTO `breaking_news` (`id`, `content`, `duration`, `creation_date`) VALUES
(1, 'NEWS!!', 60, '0000-00-00 00:00:00'),
(2, 'news!!', 23, '2025-04-28 19:11:32'),
(3, 'Test?', 1, '2025-05-01 01:07:39'),
(4, 'yes?', 1, '2025-05-01 16:33:34'),
(5, 'again', 1, '2025-05-01 16:35:50'),
(6, '2', 1, '2025-05-02 01:02:11');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Sport'),
(3, 'Entertainment'),
(6, 'Political');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `description`, `user_id`, `article_id`) VALUES
(1, 'img?', 7, 43),
(2, 'img?', 7, 43),
(3, 'd', 7, 44),
(4, 'e', 7, 44),
(5, 'f', 7, 44);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `article_id` int(11) NOT NULL,
  `saved_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`favorite_id`, `user_id`, `article_id`, `saved_at`) VALUES
(1, 33, 27, '2025-04-25 23:24:04'),
(15, 7, 44, '2025-04-30 23:45:33'),
(16, 20, 44, '2025-05-01 22:01:42');

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `GameID` int(11) NOT NULL,
  `Title` varchar(255) NOT NULL,
  `Description` varchar(500) NOT NULL,
  `img` text NOT NULL,
  `page` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`GameID`, `Title`, `Description`, `img`, `page`) VALUES
(1, 'Sudoku', 'A logic-based number puzzle where you fill a 9×9 grid so each row, column, and 3×3 box contains digits 1 to 9 without repetition', 'images/g1.jpg', 'sudoku.php#h2t'),
(2, 'Tic Tac Toe', 'You play against the computer, trying to align three X\'s or O\'s while the computer blocks or counters your moves', 'images/g2.jpg', 'XO.php#game-container'),
(3, 'Wordle', 'A word puzzle where you guess a five-letter word in six tries, with color hints for accuracy', 'images/g3.webp', 'Wordle.php#h3w'),
(4, 'Minesweeper', 'A puzzle game where you uncover tiles on a grid, avoiding hidden mines and using number clues to find safe spots', 'images/g4.jpg', 'Mine.php#h2m');

-- --------------------------------------------------------

--
-- Table structure for table `notfications`
--

CREATE TABLE `notfications` (
  `notfication_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notfication_description` varchar(1000) NOT NULL,
  `article_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notfications`
--

INSERT INTO `notfications` (`notfication_id`, `user_id`, `notfication_description`, `article_id`) VALUES
(5, 8, 'Cats Written by: Meow', 27),
(6, 20, 'Cats Written by: Meow', 27),
(7, 33, 'Cats Written by: Meow', 27),
(12, 8, 'Dogs Written by: Bark', 28),
(13, 20, 'Dogs Written by: Bark', 28),
(18, 7, 'a Written by: c', 44),
(19, 8, 'a Written by: c', 44),
(20, 20, 'a Written by: c', 44),
(21, 33, 'a Written by: c', 44);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `plan_name` varchar(255) NOT NULL,
  `plan_ID` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `popular` tinyint(1) NOT NULL DEFAULT 0,
  `features` text NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`plan_name`, `plan_ID`, `price`, `popular`, `features`, `description`) VALUES
('Free', 1, 0, 0, 'Access Articles**Translation**Games**Ads', 'Free!'),
('Premium', 2, 10, 1, 'Recieve Email Updates**Download Articles**Ads', 'Billed monthly. Ideal for light readers.'),
('Premium+', 3, 50, 0, 'Daily Briefing**Priority customer support**No Ads', 'Billed monthly. Perfect for avid readers and families.');

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

CREATE TABLE `subscription` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `auto_renew` tinyint(1) NOT NULL DEFAULT 1,
  `SubscriptionStartDate` date NOT NULL DEFAULT current_timestamp(),
  `plan_ID` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscription`
--

INSERT INTO `subscription` (`subscription_id`, `user_id`, `auto_renew`, `SubscriptionStartDate`, `plan_ID`) VALUES
(1, 7, 1, '2025-05-02', 3),
(2, 8, 1, '2025-05-02', 3),
(3, 20, 1, '2025-05-02', 3),
(4, 33, 1, '2025-05-02', 1);

--
-- Triggers `subscription`
--
DELIMITER $$
CREATE TRIGGER `before_subscription_name_update` BEFORE UPDATE ON `subscription` FOR EACH ROW BEGIN
  IF NEW.plan_ID <> OLD.plan_ID THEN
    SET NEW.SubscriptionStartDate = NOW();
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(25) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) NOT NULL,
  `role` enum('admin','member','subscriber') NOT NULL DEFAULT 'member'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `password`, `created_at`, `email`, `role`) VALUES
(7, 'mm', 'mm', '2025-04-24 18:36:53', 'bakerymai0@gmail.com', 'admin'),
(8, 'Mai', 'm', '2025-04-24 18:37:05', 'bakerymai0@gmail.comm', 'admin'),
(20, 'mo', 'momo', '2025-05-02 00:01:01', 'momoehab@gmail.com', 'subscriber'),
(33, 'member', '123', '2025-04-24 18:43:10', 'A@a.com', 'member');

--
-- Triggers `users`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `users` FOR EACH ROW BEGIN
    INSERT INTO subscription (user_id)
    VALUES (NEW.user_id);
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articles_ibfk_1` (`category_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`bookmark_id`),
  ADD UNIQUE KEY `user_article` (`user_id`,`article_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `breaking_news`
--
ALTER TABLE `breaking_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `articlllllll` (`article_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_article` (`user_id`,`article_id`),
  ADD KEY `favorites_ibfk_2` (`article_id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`GameID`);

--
-- Indexes for table `notfications`
--
ALTER TABLE `notfications`
  ADD PRIMARY KEY (`notfication_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `article_id` (`article_id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`plan_ID`);

--
-- Indexes for table `subscription`
--
ALTER TABLE `subscription`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `plan_ID` (`plan_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `breaking_news`
--
ALTER TABLE `breaking_news`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `GameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notfications`
--
ALTER TABLE `notfications`
  MODIFY `notfication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `articlllllll` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notfications`
--
ALTER TABLE `notfications`
  ADD CONSTRAINT `notfications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `notfications_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subscription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `subscription_ibfk_2` FOREIGN KEY (`plan_ID`) REFERENCES `plans` (`plan_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
