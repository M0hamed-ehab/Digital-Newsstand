-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 10:37 PM
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
(28, 'Dogs', 'Dogs are sometimes referred to as man\'s best friend because they are kept as domestic pets and are usually loyal and like being around humans. They are also helpful in reducing stress, anxiety, and depression, loneliness, encourage exercise and playfulness and even improve your cardiovascular health.', 'Bark', '2025-04-25 23:00:35', 3, '', 29),
(29, 'A Night of Feline Grace', 'There are moments when cats remind us just how majestic and mysterious they are, even in the quietest of their actions. It’s not always about loud meows or playful antics; sometimes, it’s the calm elegance of a cat simply existing in the room that speaks volumes. They glide through life with an unmatched sense of poise, and when they decide to show affection, it\'s a gift like no other.\r\n\r\nOn a night when the world is winding down, a cat’s delicate movements take center stage. With every stretch, every gentle leap, they perform an almost ritualistic dance. For those fortunate enough to witness it, it feels like the world pauses to admire the beauty of their independence. They may not need us, but they choose to be with us, and that in itself is a powerful statement. It is their quiet strength and inscrutable grace that have captured human hearts for centuries.\r\n\r\nIn the comfort of a home, there are few feelings more soothing than a cat curling up beside you, purring softly as the room quiets. Their presence has an almost magical effect, alleviating stress, bringing a sense of warmth, and creating a bond that is unique to each human and their feline companion. A cat’s love isn’t loud or demanding. It’s subtle, yet it is undeniable.', 'paws', '2025-05-03 20:21:19', 3, '', 18),
(44, 'Why Full Marks Are Well Deserved for This Project', 'This project exemplifies the perfect balance of hard work, creativity, and attention to detail. Every step, from research to execution, has been carefully crafted to ensure excellence. The technical skills are strong, but it’s the innovation and originality that truly set this project apart.\r\n\r\nIt’s not just about completing a task; it’s about pushing boundaries and transforming ideas into something meaningful. The clarity in presentation and the thoughtful design show a deep understanding of the topic. Challenges were met with creative solutions, and every element was designed with precision and care.\r\n\r\nThis project doesn’t just meet expectations—it exceeds them. It’s a reflection of dedication, skill, and a passion for excellence, making full marks not just deserved but earned.', 'Us', '2020-05-08 20:24:57', 6, '', 12),
(47, 'GAZA', 'Amid Gaza’s worsening fuel crisis under the Israeli blockade, Palestinians are converting plastic into diesel to try and keep essential services running. The production process is dangerous to humans and vehicles alike.', 'Mai', '2025-05-07 21:17:03', 6, 'gaza.webp', 23),
(48, 'Disney new theme park', 'Disney’s parks, as well as its streaming businesses — including Disney+ and Hulu — helped the company deliver $23.6 billion in revenue, a 7% on-year increase, and $4.4 billion in operating income, a 15% on-year increase.The company’s entertainment streaming businesses performed strongly, delivering $10.7 billion in revenue and $1.3 billion in operating income. While Disney+ lost 700,000 subscribers during 2025’s first quarter, the streaming platform made that up and more in the second quarter, adding 1.4 million subscribers, bringing the streamer to 126 million subscribers. Hulu, meanwhile, added 1.3 million subscribers, a 3% increase. And the company’s direct-to-consumer operating revenue also climbed, jumping to $336 million, up from $47 million the prior year.Meanwhile, the company’s sports revenue increased 5% to $4.5 billion as segment operating income decreased $91 million, to $687 million. Disney attributed the drop to increased programming and production costs from three additional college football playoff games and an added NFL game during the quarter — though these helped increase domestic advertising revenue 29%.While the company expects growth to continue during the third quarter, projecting a modest increase in Disney+ subscribers during the third quarter and double-digit increases to both entertainment and sports, Disney remains cautious about the future in the face of economic headwinds.', 'Mai', '2025-05-07 21:19:26', 3, '', 8),
(49, 'Barcelona\'s Final', 'Fittingly, after three-and-a-half hours, the 13 goals and the three invasions from the substitutes’ bench, the heavens opened: a downpour that also felt like a kind of baptism. Inter and Barcelona had drained themselves many times over, and discovered every time that they still had more to give. We were in a place beyond plans and maps, beyond shapes and tactics, beyond sanity.And so ended what turned out to be less a Champions League semi-final and more of an elongated scream, the sort of game that emerges when both sides give up on perfection and in so doing somehow manage to produce it.Inter head coach Simone Inzaghi (right) celebrates with Francesco Acerbi, whose late leveller took the game to extra time.Simone Inzaghi hails Inter for beating ‘best two sides in Europe’ on way to finalRead morePerfect theatre, perfect tension, perfect imperfection, a perfect clash of styles and a perfect balance: between flamboyant, fearless youth and grizzled, grimacing experience.Still it had to be settled, and so after Inter went two up through Lautaro Martínez and Hakan Calhanoglu, after Barcelona stunningly drew themselves level through Eric García and Dani Olmo, after the sprawling saves from Yann Sommer, after Raphinha in the 87th minute and Francesco Acerbi in the 93rd, came Davide Frattesi in the 99th. Injured at the weekend, in a game he had no right to play, Frattesi took time he had no right to take, showed composure he had no right to possess.', 'Mai', '2025-05-07 21:22:29', 1, '', 13),
(50, 'Grand Theft Auto V', 'Grand Theft Auto VI is now set to release on May 26, 2026.We are very sorry that this is later than you expected. The interest and excitement surrounding a new Grand Theft Auto has been truly humbling for our entire team. We want to thank you for your support and your patience as we work to finish the game.With every game we have released, the goal has always been to try and exceed your expectations, and Grand Theft Auto VI is no exception. We hope you understand that we need this extra time to deliver at the level of quality you expect and deserve.We look forward to sharing more information with you soon.', 'Mai', '2025-05-07 21:24:26', 3, 'gta.webp', 20),
(51, 'How Indian might stacks up against Pakistan', 'The Indian armed forces on Wednesday said they had targeted nine “terrorist camps” in air strikes in Pakistan-administered Kashmir and some deep inside the international boundary between India and Pakistan. “The targets were neutralised with clinical efficiency . . . no military establishments were targeted,” air force commander Vyomika Singh said on Wednesday.She said Indian forces used “niche” technology weapons and carefully chose targets to avoid collateral damage to civilians but did not elaborate on the specific arms or methods used in the strikes.“India has demonstrated considerable restraint in its response,” she added. “However, it must be said that the Indian armed forces are fully prepared to respond to Pakistani misadventures, if any that will escalate the situation.”Pakistani military and diplomatic officials told the Financial Times they shot down five Indian fighter jets during Wednesday’s clashes, including three French-made Rafales and two Russian-made planes. The FT could not independently verify the claim.', 'Mai', '2025-05-07 21:27:52', 6, '', 12),
(52, 'Hakimi seals PSG’s Champions', 'It was a night when Arsenal gave everything, battling until the very last, even when it looked over. Nobody should fault the spirit of Mikel Arteta’s players. They emerged with honour. But it was a night when they simply could not bend this showpiece occasion to the force of their energy and will.When it really was all over, this raucous venue pounded to a delirious beat. Paris Saint-Germain are going to their second Champions League final, deserved winners across the two legs. They will fancy their chances of a first title when they meet Inter in Munich.', 'Mai', '2025-05-07 21:29:35', 1, 'sport.avif', 11);

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
(25, 47, 48, '2025-05-08 20:18:10'),
(26, 53, 51, '2025-05-08 20:18:10'),
(27, 52, 52, '2025-05-08 20:18:10'),
(28, 42, 50, '2025-05-08 20:18:10'),
(29, 41, 47, '2025-05-08 20:18:10'),
(30, 45, 47, '2025-05-08 20:18:10'),
(31, 38, 49, '2025-05-08 20:18:10'),
(32, 46, 48, '2025-05-08 20:18:10'),
(33, 38, 28, '2025-05-08 20:18:10');

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

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`comment_id`, `description`, `user_id`, `article_id`) VALUES
(12, 'I’ve read that even just petting a dog can lower your heart rate. They really are amazing!', 46, 28),
(13, 'Imagine having to choose between breathing toxic fumes or having no power. This is the reality for so many.', 53, 47),
(14, 'Imagine being forced to take such risks just to keep hospitals and schools operational. This blockade needs to end.', 48, 47),
(15, 'It\'s crazy that Disney+ lost subscribers but then gained even more. It shows how quickly things can change in the streaming business!', 61, 48),
(16, 'Hulu’s 3% growth might not sound huge, but considering all the other streaming platforms fighting for attention, it\'s not bad at all.', 52, 48),
(17, 'What a game! This wasn’t just a match, it was an emotional rollercoaster. Both teams gave it everything they had.', 49, 49),
(18, 'Sometimes, football isn’t about perfect tactics; it’s about who can fight for it the hardest. This match was all heart and grit!', 45, 49),
(19, 'I was hoping for a quicker release, but I trust Rockstar. If it means a more polished experience, I’m good with waiting.', 50, 50),
(20, 'We got a new pope before GTA V lol.', 44, 50),
(21, 'Both sides are playing a dangerous game. Even if the targets were \'precise,\' the threat of escalation is huge. I just hope this doesn’t spiral.', 38, 51),
(22, 'That final is going to be epic. PSG vs. Inter could be one of the best matchups in recent years.', 53, 52),
(23, 'Arteta’s boys fought hard, and you’ve got to respect that. PSG just had too much firepower this time.', 33, 52);

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
(22, 39, 50, '2025-05-08 19:10:24'),
(23, 40, 28, '2025-05-08 20:05:39'),
(24, 53, 49, '2025-05-08 20:05:39'),
(25, 50, 48, '2025-05-08 20:06:02'),
(26, 44, 52, '2025-05-08 20:06:02'),
(27, 45, 47, '2025-05-08 20:06:16'),
(28, 42, 51, '2025-05-08 20:06:16');

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
(77, 38, 'Hakimi seals PSG’s Champions League final spot as bold Arsenal fall short Written by: Mai', 52),
(78, 7, 'A Night of Feline Grace Written by: paws', 29),
(79, 8, 'A Night of Feline Grace Written by: paws', 29),
(80, 20, 'A Night of Feline Grace Written by: paws', 29),
(81, 33, 'A Night of Feline Grace Written by: paws', 29),
(82, 34, 'A Night of Feline Grace Written by: paws', 29),
(83, 37, 'A Night of Feline Grace Written by: paws', 29),
(84, 38, 'A Night of Feline Grace Written by: paws', 29),
(85, 39, 'A Night of Feline Grace Written by: paws', 29),
(86, 40, 'A Night of Feline Grace Written by: paws', 29),
(87, 41, 'A Night of Feline Grace Written by: paws', 29),
(88, 42, 'A Night of Feline Grace Written by: paws', 29),
(89, 43, 'A Night of Feline Grace Written by: paws', 29),
(90, 44, 'A Night of Feline Grace Written by: paws', 29),
(91, 45, 'A Night of Feline Grace Written by: paws', 29),
(92, 46, 'A Night of Feline Grace Written by: paws', 29),
(93, 47, 'A Night of Feline Grace Written by: paws', 29),
(94, 48, 'A Night of Feline Grace Written by: paws', 29),
(95, 49, 'A Night of Feline Grace Written by: paws', 29),
(96, 50, 'A Night of Feline Grace Written by: paws', 29),
(97, 51, 'A Night of Feline Grace Written by: paws', 29),
(98, 52, 'A Night of Feline Grace Written by: paws', 29),
(99, 53, 'A Night of Feline Grace Written by: paws', 29),
(100, 54, 'A Night of Feline Grace Written by: paws', 29),
(101, 55, 'A Night of Feline Grace Written by: paws', 29),
(102, 56, 'A Night of Feline Grace Written by: paws', 29),
(103, 57, 'A Night of Feline Grace Written by: paws', 29),
(104, 58, 'A Night of Feline Grace Written by: paws', 29),
(105, 59, 'A Night of Feline Grace Written by: paws', 29),
(106, 60, 'A Night of Feline Grace Written by: paws', 29),
(107, 61, 'A Night of Feline Grace Written by: paws', 29),
(108, 62, 'A Night of Feline Grace Written by: paws', 29),
(109, 63, 'A Night of Feline Grace Written by: paws', 29),
(110, 64, 'A Night of Feline Grace Written by: paws', 29),
(111, 65, 'A Night of Feline Grace Written by: paws', 29),
(112, 66, 'A Night of Feline Grace Written by: paws', 29),
(113, 67, 'A Night of Feline Grace Written by: paws', 29),
(141, 7, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(142, 8, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(143, 20, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(144, 33, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(145, 34, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(146, 37, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(147, 38, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(148, 39, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(149, 40, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(150, 41, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(151, 42, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(152, 43, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(153, 44, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(154, 45, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(155, 46, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(156, 47, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(157, 48, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(158, 49, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(159, 50, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(160, 51, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(161, 52, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(162, 53, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(163, 54, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(164, 55, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(165, 56, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(166, 57, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(167, 58, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(168, 59, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(169, 60, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(170, 61, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(171, 62, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(172, 63, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(173, 64, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(174, 65, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(175, 66, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44),
(176, 67, 'Why Full Marks Are Well Deserved for This Project Written by: Us', 44);

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
(12, 38, 1, '2025-05-07', 1),
(13, 39, 1, '2025-05-08', 1),
(14, 40, 1, '2025-05-08', 1),
(15, 41, 1, '2025-05-08', 2),
(16, 42, 1, '2025-05-08', 3),
(17, 43, 1, '2025-05-08', 1),
(18, 44, 1, '2025-05-08', 2),
(19, 45, 1, '2025-05-08', 1),
(20, 46, 1, '2025-05-08', 2),
(21, 47, 1, '2025-05-08', 3),
(22, 48, 1, '2025-05-08', 1),
(23, 49, 1, '2025-05-08', 3),
(24, 50, 1, '2025-05-08', 1),
(25, 51, 1, '2025-05-08', 2),
(26, 52, 1, '2025-05-08', 3),
(27, 53, 1, '2025-05-08', 1),
(28, 54, 1, '2025-05-08', 2),
(29, 55, 1, '2025-05-08', 1),
(30, 56, 1, '2025-05-08', 2),
(31, 57, 1, '2025-05-08', 1),
(32, 58, 1, '2025-05-08', 1),
(33, 59, 1, '2025-05-08', 1),
(34, 60, 1, '2025-05-08', 1),
(35, 61, 1, '2025-05-08', 1),
(36, 62, 1, '2025-05-08', 1),
(37, 63, 1, '2025-05-08', 1),
(38, 64, 1, '2025-05-08', 1),
(39, 65, 1, '2025-05-08', 1),
(40, 66, 1, '2025-05-08', 1),
(41, 67, 1, '2025-05-08', 1);

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
DELIMITER $$
CREATE TRIGGER `update_user_role_on_subscription` AFTER INSERT ON `subscription` FOR EACH ROW BEGIN
        IF NEW.plan_ID = 1 THEN
    UPDATE
        users
    SET
        role = 'member'
    WHERE
        user_id = NEW.user_id ;
    END IF ;
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
(38, 'abc', 'ab', '2025-05-07 13:15:07', 'abc@gmail.com', 'member'),
(39, 'ramez', 'QXKPA', '2025-05-08 19:08:55', 'ramez38@gmail.com', 'member'),
(40, 'alicia', 'asauh', '2025-05-08 19:55:24', 'alice.johnson@webmail.com', 'member'),
(41, 'Bobby', '87as', '2025-05-08 19:55:24', 'bobby_smith@service.net', 'subscriber'),
(42, 'Charles', '9m0s3m', '2025-05-08 19:55:24', 'charles.lee@domain.org', 'admin'),
(43, 'wonder', 'BHiuN', '2025-05-08 19:55:24', 'diana_carter@fastmail.co', 'member'),
(44, 'caps', 'password123', '2025-05-08 19:55:24', 'evanw@example.net', 'subscriber'),
(45, 'derek', 'oR8k2', '2025-05-08 19:55:24', 'derek.james@webservice.com', 'member'),
(46, 'ella', 'Uu23kL', '2025-05-08 19:55:24', 'ella.b@securemail.io', 'subscriber'),
(47, 'frank', 'p4ssw0rd', '2025-05-08 19:55:24', 'frank.morris@workmail.com', 'admin'),
(48, 'gina', 'Jm3r9', '2025-05-08 19:55:24', 'gina.white@myemail.org', 'member'),
(49, 'harry', 'secur3P', '2025-05-08 19:55:24', 'harry_j@quickmail.co', 'subscriber'),
(50, 'isabella', 'v9j23k', '2025-05-08 19:55:24', 'isa.miller@protonmail.com', 'member'),
(51, 'jake', 'B9JkP', '2025-05-08 19:55:24', 'jakew@freemail.net', 'subscriber'),
(52, 'karen', 'pAssw0rD', '2025-05-08 19:55:24', 'karen.d@fastservice.io', 'admin'),
(53, 'liam', '3rt45', '2025-05-08 19:55:24', 'liam_g@emailsite.com', 'member'),
(54, 'mia', 'jI3n9', '2025-05-08 19:55:24', 'mia.r@privatesite.org', 'subscriber'),
(55, 'noah', 'ZxC12', '2025-05-08 19:55:24', 'noah.mart@websafe.co', 'member'),
(56, 'olivia', 'Yv6t9', '2025-05-08 19:55:24', 'olivia_lo@securemail.com', 'subscriber'),
(57, 'paul', 'q8W2o', '2025-05-08 19:55:24', 'paul.gonz@domainservice.org', 'admin'),
(58, 'quinn', 'r4Tn0', '2025-05-08 19:55:24', 'quinn.h@publicmail.com', 'member'),
(59, 'rose', 'tA3m9', '2025-05-08 19:55:24', 'rose_young@fastnet.io', 'subscriber'),
(60, 'samuel', '4jKp2n', '2025-05-08 19:55:24', 'samuel_r@hostmail.net', 'member'),
(61, 'tina', '2kJp4m', '2025-05-08 19:55:24', 'tina.m@myweb.org', 'subscriber'),
(62, 'ursula', '9mL3p', '2025-05-08 19:55:24', 'ursula.c@openmail.com', 'member'),
(63, 'victor', 'passw0rd!', '2025-05-08 19:55:24', 'victor.b@network.net', 'subscriber'),
(64, 'william', 'Qw4rTz', '2025-05-08 19:55:24', 'will.b@securenode.org', 'member'),
(65, 'xander', 'J3kMn9', '2025-05-08 19:55:24', 'xander_t@safeweb.io', 'subscriber'),
(66, 'yara', 'mN3v7k', '2025-05-08 19:55:24', 'yara.k@trustedmail.com', 'member'),
(67, 'zoe', 'Pw8r2L', '2025-05-08 19:55:24', 'zoe_b@proservice.org', 'subscriber');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `bookmark_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

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
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `GameID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notfications`
--
ALTER TABLE `notfications`
  MODIFY `notfication_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `plan_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription`
--
ALTER TABLE `subscription`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

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
