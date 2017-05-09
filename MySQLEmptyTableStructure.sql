-- phpMyAdmin SQL Dump
-- version 3.5.8.1
-- http://www.phpmyadmin.net
--
-- Host: gremalm.se.mysql:3306
-- Generation Time: May 09, 2017 at 11:25 PM
-- Server version: 5.5.53-MariaDB-1~wheezy
-- PHP Version: 5.4.45-0+deb7u8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gremalm_se`
--

-- --------------------------------------------------------

--
-- Table structure for table `competitions`
--

CREATE TABLE IF NOT EXISTS `competitions` (
  `competitions_id` int(11) NOT NULL AUTO_INCREMENT,
  `competitions_name` text CHARACTER SET latin1 NOT NULL,
  `competitions_visible` text CHARACTER SET latin1 NOT NULL,
  `competitions_type` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`competitions_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` text CHARACTER SET latin1 NOT NULL,
  `name` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET latin1 NOT NULL,
  `created` text CHARACTER SET latin1 NOT NULL,
  `gametype` text CHARACTER SET latin1 NOT NULL,
  `json` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `matchcrosstable`
--

CREATE TABLE IF NOT EXISTS `matchcrosstable` (
  `matchcrosstable_id` int(11) NOT NULL AUTO_INCREMENT,
  `matchcrosstable_competition_id` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_robot_id` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_robot_vs_id` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_started` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_ended` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_winner_round1` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_winner_round2` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_winner_round3` text CHARACTER SET latin1 NOT NULL,
  `matchcrosstable_comment` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`matchcrosstable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `matchdoubleelimination`
--

CREATE TABLE IF NOT EXISTS `matchdoubleelimination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `robotid` int(11) NOT NULL,
  `robotvsid` int(11) NOT NULL,
  `started` text CHARACTER SET latin1 NOT NULL,
  `updated` text CHARACTER SET latin1 NOT NULL,
  `ended` text CHARACTER SET latin1 NOT NULL,
  `winnerround1` int(11) NOT NULL,
  `winnerround2` int(11) NOT NULL,
  `winnerround3` int(11) NOT NULL,
  `comment` int(11) NOT NULL,
  `tablenumber` int(11) NOT NULL,
  `tableorder` int(11) NOT NULL,
  `tabelwinnerloser` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `matchroundrobin`
--

CREATE TABLE IF NOT EXISTS `matchroundrobin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `robotid` int(11) NOT NULL,
  `robotvsid` int(11) NOT NULL,
  `started` text CHARACTER SET latin1 NOT NULL,
  `updated` text CHARACTER SET latin1 NOT NULL,
  `winnerround1` int(11) NOT NULL,
  `winnerround2` int(11) NOT NULL,
  `winnerround3` int(11) NOT NULL,
  `comment` text CHARACTER SET latin1 NOT NULL,
  `match_order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `matchscoreboard`
--

CREATE TABLE IF NOT EXISTS `matchscoreboard` (
  `matchscoreboard_id` int(11) NOT NULL AUTO_INCREMENT,
  `matchscoreboard_competition_id` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_robot_id` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_last_edited` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_line` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_collect` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_terrain` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_labyrinth` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_spex_performance` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_spex_design` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_spex_teknik` text CHARACTER SET latin1 NOT NULL,
  `matchscoreboard_bonus` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`matchscoreboard_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `matchwinnersbracket`
--

CREATE TABLE IF NOT EXISTS `matchwinnersbracket` (
  `matchwinnersbracket_id` int(11) NOT NULL AUTO_INCREMENT,
  `matchwinnersbracket_matchnumber` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_competition_id` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_robot_id` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_robot_vs_id` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_started` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_ended` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_winner_round1` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_winner_round2` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_winner_round3` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_winner_round4` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_winner_round5` text CHARACTER SET latin1 NOT NULL,
  `matchwinnersbracket_comment` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`matchwinnersbracket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE IF NOT EXISTS `participants` (
  `participant_id` int(11) NOT NULL AUTO_INCREMENT,
  `participant_name` text CHARACTER SET latin1 NOT NULL,
  `created` text CHARACTER SET latin1 NOT NULL,
  `telephone` text CHARACTER SET latin1 NOT NULL,
  `mail` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`participant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_competition_robot`
--

CREATE TABLE IF NOT EXISTS `rel_competition_robot` (
  `rel_competitions_robot_id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_robot_id` text CHARACTER SET latin1 NOT NULL,
  `rel_rel_competition_id` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`rel_competitions_robot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_event_robot`
--

CREATE TABLE IF NOT EXISTS `rel_event_robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `robot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_event_tournament`
--

CREATE TABLE IF NOT EXISTS `rel_event_tournament` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `tournament_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_game_robot`
--

CREATE TABLE IF NOT EXISTS `rel_game_robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `robot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_game_robot_doubleelemination`
--

CREATE TABLE IF NOT EXISTS `rel_game_robot_doubleelemination` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `robot_id` int(11) NOT NULL,
  `overunder` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_game_scoreboard_matches`
--

CREATE TABLE IF NOT EXISTS `rel_game_scoreboard_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `robotid` int(11) NOT NULL,
  `roundid` int(11) NOT NULL,
  `score` text CHARACTER SET latin1 NOT NULL,
  `comments` text CHARACTER SET latin1 NOT NULL,
  `started` text CHARACTER SET latin1 NOT NULL,
  `updated` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_participant_team`
--

CREATE TABLE IF NOT EXISTS `rel_participant_team` (
  `rel_id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_participant_id` int(11) NOT NULL,
  `rel_team_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_robot_team`
--

CREATE TABLE IF NOT EXISTS `rel_robot_team` (
  `rel_robot_team_id` int(11) NOT NULL AUTO_INCREMENT,
  `rel_robot_id` int(11) NOT NULL,
  `rel_team_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_robot_team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_tournament_game`
--

CREATE TABLE IF NOT EXISTS `rel_tournament_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rel_tournament_robot`
--

CREATE TABLE IF NOT EXISTS `rel_tournament_robot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tournament_id` int(11) NOT NULL,
  `robot_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `robots`
--

CREATE TABLE IF NOT EXISTS `robots` (
  `robot_id` int(11) NOT NULL AUTO_INCREMENT,
  `robot_name` text CHARACTER SET latin1 NOT NULL,
  `robot_class` text CHARACTER SET latin1 NOT NULL,
  `robot_weight` text CHARACTER SET latin1 NOT NULL COMMENT 'g',
  `robot_width` text CHARACTER SET latin1 NOT NULL COMMENT 'mm',
  `robot_depth` text CHARACTER SET latin1 NOT NULL COMMENT 'mm',
  `robot_height` text CHARACTER SET latin1 NOT NULL COMMENT 'mm',
  `robot_features` longtext CHARACTER SET latin1 NOT NULL,
  `robot_image` text CHARACTER SET latin1 NOT NULL COMMENT 'url_img',
  `robot_imagesmall` text CHARACTER SET latin1 NOT NULL COMMENT 'url_img',
  `robot_weighin` text CHARACTER SET latin1 NOT NULL,
  `created` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`robot_id`),
  UNIQUE KEY `robot_id` (`robot_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scoreboardrounds`
--

CREATE TABLE IF NOT EXISTS `scoreboardrounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_id` int(11) NOT NULL,
  `name` text CHARACTER SET latin1 NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_name` text CHARACTER SET latin1 NOT NULL,
  `team_mail` text CHARACTER SET latin1 NOT NULL,
  `team_telephone` text CHARACTER SET latin1 NOT NULL,
  `team_city` text CHARACTER SET latin1 NOT NULL,
  `team_background` longtext CHARACTER SET latin1 NOT NULL,
  `created` text CHARACTER SET latin1 NOT NULL,
  `url` text CHARACTER SET latin1 NOT NULL,
  `organisation` text CHARACTER SET latin1 NOT NULL,
  `teamleaderid` int(11) NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams_reg`
--

CREATE TABLE IF NOT EXISTS `teams_reg` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `team_Submitted` text CHARACTER SET latin1 NOT NULL,
  `team_lagnamn` text CHARACTER SET latin1 NOT NULL,
  `team_klass` text CHARACTER SET latin1 NOT NULL,
  `team_url` text CHARACTER SET latin1 NOT NULL,
  `team_organisation` text CHARACTER SET latin1 NOT NULL,
  `team_lagledare_namn` text CHARACTER SET latin1 NOT NULL,
  `team_lagledare_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_lagledare_epost` text CHARACTER SET latin1 NOT NULL,
  `team_m1_namn` text CHARACTER SET latin1 NOT NULL,
  `team_m1_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_m1_epost` text CHARACTER SET latin1 NOT NULL,
  `team_m2_namn` text CHARACTER SET latin1 NOT NULL,
  `team_m2_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_m2_epost` text CHARACTER SET latin1 NOT NULL,
  `team_m3_namn` text CHARACTER SET latin1 NOT NULL,
  `team_m3_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_m3_epost` text CHARACTER SET latin1 NOT NULL,
  `team_m4_namn` text CHARACTER SET latin1 NOT NULL,
  `team_m4_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_m4_epost` text CHARACTER SET latin1 NOT NULL,
  `team_m5_namn` text CHARACTER SET latin1 NOT NULL,
  `team_m5_telefon` text CHARACTER SET latin1 NOT NULL,
  `team_m5_epost` text CHARACTER SET latin1 NOT NULL,
  `team_meddelande` text CHARACTER SET latin1 NOT NULL,
  `team_Submitted_From` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE IF NOT EXISTS `tournaments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created` text CHARACTER SET latin1 NOT NULL,
  `name` text CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
