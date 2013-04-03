-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 11. Nov 2011 um 12:15
-- Server Version: 5.0.77
-- PHP-Version: 5.2.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `usr_web707_5`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_anticheat`
--

CREATE TABLE IF NOT EXISTS `cbg_anticheat` (
  `id` int(11) NOT NULL auto_increment,
  `player1` int(11) NOT NULL,
  `player2` int(11) NOT NULL,
  `time` varchar(255) collate utf8_unicode_ci NOT NULL,
  `score` float NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_attack`
--

CREATE TABLE IF NOT EXISTS `cbg_attack` (
  `id` int(11) NOT NULL auto_increment,
  `time` varchar(255) collate utf8_unicode_ci NOT NULL,
  `server` text collate utf8_unicode_ci NOT NULL,
  `get` text collate utf8_unicode_ci NOT NULL,
  `post` text collate utf8_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_blacklist_username`
--

CREATE TABLE IF NOT EXISTS `cbg_blacklist_username` (
  `id` int(11) NOT NULL auto_increment,
  `string` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Daten für Tabelle `cbg_blacklist_username`
--

INSERT INTO `cbg_blacklist_username` (`id`, `string`) VALUES
(1, 'matthes'),
(2, 'admin'),
(3, 'leader'),
(4, 'clan'),
(5, 'moderator'),
(6, 'cbg'),
(7, 'root'),
(8, 'clonk'),
(9, 'ccan'),
(10, 'mod');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_blacklist_word`
--

CREATE TABLE IF NOT EXISTS `cbg_blacklist_word` (
  `id` int(11) NOT NULL auto_increment,
  `string` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_config`
--

CREATE TABLE IF NOT EXISTS `cbg_config` (
  `config` varchar(255) collate utf8_unicode_ci NOT NULL,
  `value` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`config`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cbg_config`
--

INSERT INTO `cbg_config` (`config`, `value`) VALUES
('user_group_default', '1'),
('user_invite_maximum', '0'),
('user_message_length_max', '600'),
('user_message_length_min', '10'),
('user_registration_length_max', '12'),
('user_registration_length_min', '3'),
('user_registration_open', '0'),
('user_registration_password_length_max', '64'),
('user_registration_password_length_min', '6'),
('user_login_keep', '600'),
('user_login_max', '86400');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_group`
--

CREATE TABLE IF NOT EXISTS `cbg_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(25) character set latin1 NOT NULL,
  `description` text collate utf8_unicode_ci NOT NULL,
  `image` varchar(255) character set latin1 NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Daten für Tabelle `cbg_group`
--

INSERT INTO `cbg_group` (`id`, `name`, `description`, `image`) VALUES
(1, 'Spieler', 'Normale Spieler.', 'user.png'),
(2, 'Probe-Moderator', 'Probe-Moderatoren mit grundlegenden Moderationsfunktionen ohne sicherheitskritischen Zugriff.', 'mod_trial.png'),
(3, 'Moderator', 'Feste Moderatoren mit erweiterten Moderationsfunktionen.', 'mod.png'),
(4, 'Administrator', 'Kompletter Systemzugriff inklusive Bearbeiten von Benutzern und Gruppen.', 'admin.png'),
(5, 'Gründer', 'Kompletter Systemzugriff wie Administrator, Ehrenrang.', 'founder.png'),
(6, 'Entwickler', 'Entwickler ohne besondere Rechte.', 'team.png'),
(7, 'Ehrenspieler', 'Ausgezeichnete Spieler ohne besondere Rechte.', 'special.png');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_group_right`
--

CREATE TABLE IF NOT EXISTS `cbg_group_right` (
  `group` int(11) NOT NULL,
  `right` int(11) NOT NULL,
  PRIMARY KEY  (`group`,`right`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `cbg_group_right`
--

INSERT INTO `cbg_group_right` (`group`, `right`) VALUES
(2, 1),
(2, 3),
(2, 5),
(2, 8),
(2, 12),
(3, 1),
(3, 3),
(3, 4),
(3, 5),
(3, 8),
(3, 12),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(4, 6),
(4, 7),
(4, 8),
(4, 9),
(4, 10),
(4, 11),
(4, 12),
(4, 13),
(4, 14),
(4, 15),
(4, 16),
(5, 1),
(5, 2),
(5, 3),
(5, 4),
(5, 5),
(5, 6),
(5, 7),
(5, 8),
(5, 9),
(5, 10),
(5, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(5, 16),
(6, 12);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_key`
--

CREATE TABLE IF NOT EXISTS `cbg_key` (
  `id` int(11) NOT NULL auto_increment,
  `key` varchar(20) collate utf8_unicode_ci NOT NULL,
  `valid` tinyint(4) NOT NULL,
  `by` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_maintenance`
--

CREATE TABLE IF NOT EXISTS `cbg_maintenance` (
  `id` int(11) NOT NULL auto_increment,
  `from` varchar(255) collate utf8_unicode_ci NOT NULL,
  `until` varchar(255) collate utf8_unicode_ci NOT NULL,
  `reason` varchar(255) collate utf8_unicode_ci NOT NULL,
  `by` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_right`
--

CREATE TABLE IF NOT EXISTS `cbg_right` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Daten für Tabelle `cbg_right`
--

INSERT INTO `cbg_right` (`id`, `name`) VALUES
(1, 'admin_view'),
(2, 'user_add'),
(3, 'user_ban'),
(4, 'user_edit'),
(5, 'user_moderate'),
(6, 'project_settings'),
(7, 'user_mail_access'),
(8, 'user_view'),
(9, 'user_edit_all'),
(10, 'project_maintenance'),
(11, 'user_message_global'),
(12, 'user_support'),
(13, 'user_remove'),
(14, 'group_view'),
(15, 'group_edit'),
(16, 'group_add');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user`
--

CREATE TABLE IF NOT EXISTS `cbg_user` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `password` varchar(255) collate utf8_unicode_ci NOT NULL,
  `registration` varchar(255) collate utf8_unicode_ci NOT NULL,
  `invited_by` int(11) NOT NULL,
  `mail` varchar(255) collate utf8_unicode_ci NOT NULL,
  `group` int(11) NOT NULL,
  `points` int(11) NOT NULL default '0',
  `invites` int(11) NOT NULL,
  `lastactivity` varchar(255) collate utf8_unicode_ci NOT NULL default '0',
  `lastlogin` varchar(255) collate utf8_unicode_ci NOT NULL,
  `lastip` varchar(255) collate utf8_unicode_ci NOT NULL,
  `lastuseragent` varchar(255) collate utf8_unicode_ci NOT NULL,
  `logout` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_ban`
--

CREATE TABLE IF NOT EXISTS `cbg_user_ban` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `by` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  `time` varchar(255) collate utf8_unicode_ci NOT NULL,
  `until` varchar(255) collate utf8_unicode_ci NOT NULL,
  `comment` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=23 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_history`
--

CREATE TABLE IF NOT EXISTS `cbg_user_history` (
  `id` int(11) NOT NULL auto_increment,
  `user` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `object` int(11) NOT NULL,
  `details` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_message`
--

CREATE TABLE IF NOT EXISTS `cbg_user_message` (
  `id` int(11) NOT NULL auto_increment,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `time` varchar(255) collate utf8_unicode_ci NOT NULL,
  `message` text collate utf8_unicode_ci NOT NULL,
  `hash` varchar(255) collate utf8_unicode_ci NOT NULL,
  `read` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_settlement`
--

CREATE TABLE IF NOT EXISTS `cbg_user_settlement` (
  `id` int(11) NOT NULL auto_increment,
  `owner` int(11) NOT NULL,
  `name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `search_left` varchar(255) collate utf8_unicode_ci NOT NULL default '0',
  `search_right` varchar(255) collate utf8_unicode_ci NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_settlement_building`
--

CREATE TABLE IF NOT EXISTS `cbg_user_settlement_building` (
  `id` int(11) NOT NULL auto_increment,
  `building` varchar(255) collate utf8_unicode_ci NOT NULL,
  `settlement` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `completion` varchar(255) collate utf8_unicode_ci NOT NULL,
  `order` decimal(10,1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_settlement_building_mine`
--

CREATE TABLE IF NOT EXISTS `cbg_user_settlement_building_mine` (
  `id` int(11) NOT NULL auto_increment,
  `building` int(11) NOT NULL,
  `type` varchar(255) collate utf8_unicode_ci NOT NULL,
  `total` int(11) NOT NULL,
  `left` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cbg_user_settlement_object`
--

CREATE TABLE IF NOT EXISTS `cbg_user_settlement_object` (
  `settlement` int(11) NOT NULL,
  `object` varchar(255) collate utf8_unicode_ci NOT NULL,
  `amount` int(11) NOT NULL,
  UNIQUE KEY `settlement` (`settlement`,`object`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
