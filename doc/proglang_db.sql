-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Okt 2019 um 15:36
-- Server-Version: 10.4.6-MariaDB
-- PHP-Version: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `proglang_db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE `article` (
  `ID_Article` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `helpfulness` int(11) NOT NULL DEFAULT 0,
  `author_User_ID` int(11) DEFAULT NULL,
  `timeCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`ID_Article`, `name`, `helpfulness`, `author_User_ID`, `timeCreated`) VALUES
(10, 'C', 2, 1, '2019-10-24 09:21:16');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment`
--

CREATE TABLE `comment` (
  `ID_Comment` int(11) NOT NULL,
  `thread_Post_ID` int(11) NOT NULL,
  `parent_Comment_ID` int(11) DEFAULT NULL,
  `creator_User_ID` int(11) DEFAULT NULL,
  `contentText` text COLLATE utf8_bin NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `timeCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `post`
--

CREATE TABLE `post` (
  `ID_Post` int(11) NOT NULL,
  `lang_Article_ID` int(11) NOT NULL,
  `creator_User_ID` int(11) DEFAULT NULL,
  `contentTitle` varchar(50) COLLATE utf8_bin NOT NULL,
  `contentText` text COLLATE utf8_bin DEFAULT NULL,
  `image` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `score` int(11) NOT NULL,
  `timeCreated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `ID_User` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `passwordHash` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL DEFAULT 'This user hasn\'t set their description yet.',
  `trustScore` int(11) NOT NULL DEFAULT 5,
  `joinDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `picture` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '/assets/img/profilepic/placeholder.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`ID_User`, `username`, `passwordHash`, `description`, `trustScore`, `joinDate`, `picture`) VALUES
(1, 'ProgLang Admin', '$2y$10$NGRHl2o1MswnlYlUnPhSPev1bKyYTt2VOsBQAD7wB.ZA4yvrJ1EC6', 'This the official account of the ProgLang Development team.', 10, '2019-10-04 12:47:09', '/assets/img/profilepic/pp_5d9ecdd48c1d7.png'),
(2, 'testuser', '$2y$10$xVwR2h8gv1qfZ62/Xy6bsO1ykNKfenxpd/g.AG4lE3/PzKUxw0PA.', 'A user created to test the database early in ProgLang\'s development.', 5, '2019-10-04 12:51:29', '../../assets/img/profilepic/pp_5db1a7064357f.jpeg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vote`
--

CREATE TABLE `vote` (
  `ID_Vote` int(11) NOT NULL,
  `Post_ID` int(11) NOT NULL,
  `User_ID` int(11) NOT NULL,
  `isUpvote` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Daten für Tabelle `vote`
--

INSERT INTO `vote` (`ID_Vote`, `Post_ID`, `User_ID`, `isUpvote`) VALUES
(1, 1, 1, 1),
(4, 3, 1, 1),
(5, 3, 2, 1),
(6, 1, 2, 1),
(7, 5, 2, 1),
(8, 4, 2, 0),
(9, 4, 1, 1),
(10, 5, 1, 1),
(11, 6, 1, 1),
(12, 6, 2, 1),
(13, 7, 1, 1),
(14, 8, 1, 1),
(15, 12, 1, 1),
(16, 12, 2, 1),
(17, 15, 1, 1),
(18, 19, 1, 1),
(19, 20, 1, 0);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `article`
--
ALTER TABLE `article`
  ADD UNIQUE KEY `ID_Article` (`ID_Article`),
  ADD UNIQUE KEY `idx_articlename` (`name`),
  ADD KEY `author_User_ID` (`author_User_ID`);

--
-- Indizes für die Tabelle `comment`
--
ALTER TABLE `comment`
  ADD UNIQUE KEY `ID_Comment` (`ID_Comment`),
  ADD KEY `thread_Post_ID` (`thread_Post_ID`),
  ADD KEY `comment_ibfk_2` (`parent_Comment_ID`),
  ADD KEY `comment_ibfk_3` (`creator_User_ID`);

--
-- Indizes für die Tabelle `post`
--
ALTER TABLE `post`
  ADD UNIQUE KEY `ID_Post` (`ID_Post`),
  ADD KEY `creator_User_ID` (`creator_User_ID`),
  ADD KEY `lang_Article_ID` (`lang_Article_ID`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `ID_User` (`ID_User`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indizes für die Tabelle `vote`
--
ALTER TABLE `vote`
  ADD UNIQUE KEY `Vote_ID` (`ID_Vote`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `ID_Article` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT für Tabelle `comment`
--
ALTER TABLE `comment`
  MODIFY `ID_Comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT für Tabelle `post`
--
ALTER TABLE `post`
  MODIFY `ID_Post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `ID_User` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT für Tabelle `vote`
--
ALTER TABLE `vote`
  MODIFY `ID_Vote` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`author_User_ID`) REFERENCES `user` (`ID_User`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints der Tabelle `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`thread_Post_ID`) REFERENCES `post` (`ID_Post`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`parent_Comment_ID`) REFERENCES `comment` (`ID_Comment`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`creator_User_ID`) REFERENCES `user` (`ID_User`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`lang_Article_ID`) REFERENCES `article` (`ID_Article`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_ibfk_2` FOREIGN KEY (`creator_User_ID`) REFERENCES `user` (`ID_User`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
