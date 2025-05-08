-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 11:57 PM
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
(28, 'Dogs', 'Dogs are sometimes referred to as man\'s best friend because they are kept as domestic pets and are usually loyal and like being around humans. They are also helpful in reducing stress, anxiety, and depression, loneliness, encourage exercise and playfulness and even improve your cardiovascular health.', 'Bark', '2025-04-25 23:00:35', 3, '', 28),
(47, 'GAZA', 'Amid Gaza’s worsening fuel crisis under the Israeli blockade, Palestinians are converting plastic into diesel to try and keep essential services running. The production process is dangerous to humans and vehicles alike.', 'Mai', '2025-05-07 21:17:03', 6, 'gaza.webp', 2),
(48, 'Disney new theme park', 'Disney’s parks, as well as its streaming businesses — including Disney+ and Hulu — helped the company deliver $23.6 billion in revenue, a 7% on-year increase, and $4.4 billion in operating income, a 15% on-year increase.The company’s entertainment streaming businesses performed strongly, delivering $10.7 billion in revenue and $1.3 billion in operating income. While Disney+ lost 700,000 subscribers during 2025’s first quarter, the streaming platform made that up and more in the second quarter, adding 1.4 million subscribers, bringing the streamer to 126 million subscribers. Hulu, meanwhile, added 1.3 million subscribers, a 3% increase. And the company’s direct-to-consumer operating revenue also climbed, jumping to $336 million, up from $47 million the prior year.Meanwhile, the company’s sports revenue increased 5% to $4.5 billion as segment operating income decreased $91 million, to $687 million. Disney attributed the drop to increased programming and production costs from three additional college football playoff games and an added NFL game during the quarter — though these helped increase domestic advertising revenue 29%.While the company expects growth to continue during the third quarter, projecting a modest increase in Disney+ subscribers during the third quarter and double-digit increases to both entertainment and sports, Disney remains cautious about the future in the face of economic headwinds.', 'Mai', '2025-05-07 21:19:26', 3, '', 1),
(49, 'Barcelona\'s Final', 'Fittingly, after three-and-a-half hours, the 13 goals and the three invasions from the substitutes’ bench, the heavens opened: a downpour that also felt like a kind of baptism. Inter and Barcelona had drained themselves many times over, and discovered every time that they still had more to give. We were in a place beyond plans and maps, beyond shapes and tactics, beyond sanity.And so ended what turned out to be less a Champions League semi-final and more of an elongated scream, the sort of game that emerges when both sides give up on perfection and in so doing somehow manage to produce it.Inter head coach Simone Inzaghi (right) celebrates with Francesco Acerbi, whose late leveller took the game to extra time.Simone Inzaghi hails Inter for beating ‘best two sides in Europe’ on way to finalRead morePerfect theatre, perfect tension, perfect imperfection, a perfect clash of styles and a perfect balance: between flamboyant, fearless youth and grizzled, grimacing experience.Still it had to be settled, and so after Inter went two up through Lautaro Martínez and Hakan Calhanoglu, after Barcelona stunningly drew themselves level through Eric García and Dani Olmo, after the sprawling saves from Yann Sommer, after Raphinha in the 87th minute and Francesco Acerbi in the 93rd, came Davide Frattesi in the 99th. Injured at the weekend, in a game he had no right to play, Frattesi took time he had no right to take, showed composure he had no right to possess.', 'Mai', '2025-05-07 21:22:29', 1, '', 1),
(50, 'Grand Theft Auto V', 'Grand Theft Auto VI is now set to release on May 26, 2026.We are very sorry that this is later than you expected. The interest and excitement surrounding a new Grand Theft Auto has been truly humbling for our entire team. We want to thank you for your support and your patience as we work to finish the game.With every game we have released, the goal has always been to try and exceed your expectations, and Grand Theft Auto VI is no exception. We hope you understand that we need this extra time to deliver at the level of quality you expect and deserve.We look forward to sharing more information with you soon.', 'Mai', '2025-05-07 21:24:26', 3, 'gta.webp', 1),
(51, 'How Indian might stacks up against Pakistan', '	Please use the sharing tools found via the share button at the top or side of articles. Copying articles to share with others is a breach of FT.com T&Cs and Copyright Policy. Email licensing@ft.com to buy additional rights. Subscribers may share up to 10 or 20 articles per month using the gift article service. More information can be found at https://www.ft.com/tour.	https://www.ft.com/content/9f1f4841-ed05-4ed1-9c71-e2cb9bb14f88	The Indian armed forces on Wednesday said they had targeted nine “terrorist camps” in air strikes in Pakistan-administered Kashmir and some deep inside the international boundary between India and Pakistan. “The targets were neutralised with clinical efficiency . . . no military establishments were targeted,” air force commander Vyomika Singh said on Wednesday.She said Indian forces used “niche” technology weapons and carefully chose targets to avoid collateral damage to civilians but did not elaborate on the specific arms or methods used in the strikes.“India has demonstrated considerable restraint in its response,” she added. “However, it must be said that the Indian armed forces are fully prepared to respond to Pakistani misadventures, if any that will escalate the situation.”Pakistani military and diplomatic officials told the Financial Times they shot down five Indian fighter jets during Wednesday’s clashes, including three French-made Rafales and two Russian-made planes. The FT could not independently verify the claim.', 'Mai', '2025-05-07 21:27:52', 6, '', 2),
(52, 'Hakimi seals PSG’s Champions', 'It was a night when Arsenal gave everything, battling until the very last, even when it looked over. Nobody should fault the spirit of Mikel Arteta’s players. They emerged with honour. But it was a night when they simply could not bend this showpiece occasion to the force of their energy and will.When it really was all over, this raucous venue pounded to a delirious beat. Paris Saint-Germain are going to their second Champions League final, deserved winners across the two legs. They will fancy their chances of a first title when they meet Inter in Munich.', 'Mai', '2025-05-07 21:29:35', 1, 'sport.avif', 1);

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
(6, '2', 1, '2025-05-02 01:02:11'),
(7, 'ok?', 1, '2025-05-07 18:05:53');

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
(12, 8, 'Dogs Written by: Bark', 28),
(13, 20, 'Dogs Written by: Bark', 28),
(36, 7, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(37, 8, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(38, 20, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(39, 33, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(40, 34, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(41, 37, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(42, 38, 'Palestinians turn plastic into diesel amid fuel shortage in Gaza Written by: Mai', 47),
(43, 7, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(44, 8, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(45, 20, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(46, 33, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(47, 34, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(48, 37, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(49, 38, 'Disney announces a new theme park in Abu Dhabi, its first new resort in a generation Written by: Mai', 48),
(50, 7, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(51, 8, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(52, 20, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(53, 33, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(54, 34, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(55, 37, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(56, 38, ' Frattesi fires Inter into final as Barcelona fall short in seven-goal instant classic Written by: Mai', 49),
(57, 7, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(58, 8, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(59, 20, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(60, 33, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(61, 34, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(62, 37, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(63, 38, 'Grand Theft Auto VI is Now Coming May 26, 2026 Written by: Mai', 50),
(64, 7, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(65, 8, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(66, 20, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(67, 33, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(68, 34, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(69, 37, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(70, 38, 'Military briefing: How Indian might stacks up against Pakistan Written by: Mai', 51),
(71, 7, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(72, 8, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(73, 20, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(74, 33, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(75, 34, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(76, 37, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(77, 38, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52);

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
(1, 7, 1, '2025-05-07', 3),
(2, 8, 1, '2025-05-02', 3),
(3, 20, 1, '2025-05-02', 3),
(4, 33, 1, '2025-05-02', 1),
(8, 34, 1, '2025-05-05', 2),
(11, 37, 1, '2025-05-07', 1),
(12, 38, 1, '2025-05-07', 1);

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
(7, 'mm', 'mm', '2025-05-06 18:44:38', 'bakerymai0@gmail.com', 'admin'),
(8, 'Mai', 'm', '2025-04-24 18:37:05', 'bakerymai0@gmail.comm', 'admin'),
(20, 'mo', 'momo', '2025-05-02 00:01:01', 'momoehab@gmail.com', 'subscriber'),
(33, 'member', '123', '2025-04-24 18:43:10', 'A@a.com', 'member'),
(34, 'Tamer', 'TP41N', '2025-05-04 22:02:58', 'T4tamer@gmail.com', 'subscriber'),
(37, 'ab', 'ab', '2025-05-07 13:14:27', 'ab@gmail.com', 'member'),
(38, 'abc', 'ab', '2025-05-07 13:15:07', 'abc@gmail.com', 'member');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `breaking_news`
--
ALTER TABLE `breaking_news`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `GameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notfications`
--
ALTER TABLE `notfications`
  MODIFY `notfication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

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
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `articlllllll` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `notfications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notfications_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subscription_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subscription_ibfk_2` FOREIGN KEY (`plan_ID`) REFERENCES `plans` (`plan_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
