-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 10.150.1.49
-- Erstellungszeit: 02. Jun 2022 um 17:12
-- Server-Version: 10.5.12-MariaDB-0+deb11u1
-- PHP-Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cose`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__api-token`
--

CREATE TABLE `dload__api-token` (
  `id` int(11) NOT NULL,
  `token` varchar(190) NOT NULL,
  `name` mediumtext NOT NULL,
  `apiuri` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__module-rank`
--

CREATE TABLE `dload__module-rank` (
  `id` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `name` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__pictures`
--

CREATE TABLE `dload__pictures` (
  `id` int(11) NOT NULL,
  `token` varchar(145) NOT NULL,
  `title` mediumtext NOT NULL,
  `description` longtext DEFAULT NULL,
  `picurl` mediumtext NOT NULL,
  `preview` longtext NOT NULL,
  `source` mediumtext DEFAULT NULL,
  `sourcetype` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `aid` int(11) NOT NULL DEFAULT 5,
  `creationdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__pictures-validate`
--

CREATE TABLE `dload__pictures-validate` (
  `id` int(11) NOT NULL,
  `picture-id` int(11) NOT NULL,
  `user-id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__point-origin`
--

CREATE TABLE `dload__point-origin` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__ranks`
--

CREATE TABLE `dload__ranks` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL DEFAULT 50,
  `name` mediumtext NOT NULL,
  `icon` varchar(140) NOT NULL DEFAULT '''newbie.svg'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dload__ranks`
--

INSERT INTO `dload__ranks` (`id`, `value`, `name`, `icon`) VALUES
(1, 50, 'Neuling', 'newbie.svg'),
(2, 100, 'Inspektor', 'inspector.svg'),
(3, 200, 'Veteran', 'veteran.svg');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__ranksystem`
--

CREATE TABLE `dload__ranksystem` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `oid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__rights-tools`
--

CREATE TABLE `dload__rights-tools` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__roles`
--

CREATE TABLE `dload__roles` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL DEFAULT 0,
  `name` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dload__roles`
--

INSERT INTO `dload__roles` (`id`, `value`, `name`) VALUES
(4, 1, 'Unauthorized User'),
(5, 2, 'Autorisierter Nutzer'),
(7, 20, 'Administrator'),
(8, 10, 'Mitarbeiter');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__session`
--

CREATE TABLE `dload__session` (
  `ses_id` varchar(190) NOT NULL,
  `ses_time` int(11) NOT NULL,
  `ses_value` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__source_type`
--

CREATE TABLE `dload__source_type` (
  `id` int(11) NOT NULL,
  `name` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `dload__source_type`
--

INSERT INTO `dload__source_type` (`id`, `name`) VALUES
(1, 'Literatur'),
(2, 'Webseite'),
(3, 'Zeitzeuge'),
(4, 'Quellenmaterial'),
(5, 'Sonstiges');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__stories`
--

CREATE TABLE `dload__stories` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `storie_token` varchar(140) NOT NULL,
  `title` mediumtext NOT NULL,
  `story` longtext NOT NULL,
  `aid` int(11) NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `points_received` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__stories-validate`
--

CREATE TABLE `dload__stories-validate` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL COMMENT 'Storie-ID',
  `uid` int(11) NOT NULL COMMENT 'User-ID',
  `value` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__user-login`
--

CREATE TABLE `dload__user-login` (
  `id` int(11) NOT NULL,
  `name` varchar(190) NOT NULL,
  `password` longtext NOT NULL,
  `firstname` mediumtext DEFAULT NULL,
  `lastname` mediumtext DEFAULT NULL,
  `email` mediumtext NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT 1,
  `mailvalidated` tinyint(1) NOT NULL DEFAULT 0,
  `role` int(11) NOT NULL,
  `creationdate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__user_browserinfo`
--

CREATE TABLE `dload__user_browserinfo` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `browserName` varchar(140) NOT NULL,
  `browserVersion` varchar(140) NOT NULL,
  `plattform` varchar(140) NOT NULL,
  `userAgent` mediumtext NOT NULL,
  `realName` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__user_requests`
--

CREATE TABLE `dload__user_requests` (
  `id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(140) NOT NULL,
  `module` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dload__visitors`
--

CREATE TABLE `dload__visitors` (
  `id` int(11) NOT NULL,
  `ip` varchar(140) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` varchar(140) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `dload__api-token`
--
ALTER TABLE `dload__api-token`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indizes für die Tabelle `dload__module-rank`
--
ALTER TABLE `dload__module-rank`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `aid` (`aid`,`rid`),
  ADD KEY `rank-id-constraint` (`rid`);

--
-- Indizes für die Tabelle `dload__pictures`
--
ALTER TABLE `dload__pictures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pic-token` (`token`),
  ADD KEY `uid-picture` (`uid`),
  ADD KEY `aid-picture` (`aid`),
  ADD KEY `src-type-pic-constraint` (`sourcetype`);

--
-- Indizes für die Tabelle `dload__pictures-validate`
--
ALTER TABLE `dload__pictures-validate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `validate-fk-picture-id` (`picture-id`),
  ADD KEY `validate-fk-user-id` (`user-id`);

--
-- Indizes für die Tabelle `dload__point-origin`
--
ALTER TABLE `dload__point-origin`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dload__ranks`
--
ALTER TABLE `dload__ranks`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dload__ranksystem`
--
ALTER TABLE `dload__ranksystem`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `aid` (`aid`),
  ADD KEY `oid` (`oid`);

--
-- Indizes für die Tabelle `dload__rights-tools`
--
ALTER TABLE `dload__rights-tools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unq-apiid-uid` (`aid`,`uid`),
  ADD KEY `userID` (`uid`),
  ADD KEY `RoleID` (`role`);

--
-- Indizes für die Tabelle `dload__roles`
--
ALTER TABLE `dload__roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indizes für die Tabelle `dload__session`
--
ALTER TABLE `dload__session`
  ADD PRIMARY KEY (`ses_id`);

--
-- Indizes für die Tabelle `dload__source_type`
--
ALTER TABLE `dload__source_type`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dload__stories`
--
ALTER TABLE `dload__stories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `storie_token` (`storie_token`),
  ADD KEY `uid_stories` (`user_id`),
  ADD KEY `aid_stories` (`aid`);

--
-- Indizes für die Tabelle `dload__stories-validate`
--
ALTER TABLE `dload__stories-validate`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sid-uid` (`sid`,`uid`),
  ADD KEY `fk-uid-sid-val` (`uid`);

--
-- Indizes für die Tabelle `dload__user-login`
--
ALTER TABLE `dload__user-login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `role-id-verk` (`role`);

--
-- Indizes für die Tabelle `dload__user_browserinfo`
--
ALTER TABLE `dload__user_browserinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `dload__user_requests`
--
ALTER TABLE `dload__user_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module-request-statistics` (`module`);

--
-- Indizes für die Tabelle `dload__visitors`
--
ALTER TABLE `dload__visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `dload__api-token`
--
ALTER TABLE `dload__api-token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__module-rank`
--
ALTER TABLE `dload__module-rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__pictures`
--
ALTER TABLE `dload__pictures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__pictures-validate`
--
ALTER TABLE `dload__pictures-validate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__point-origin`
--
ALTER TABLE `dload__point-origin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__ranks`
--
ALTER TABLE `dload__ranks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT für Tabelle `dload__ranksystem`
--
ALTER TABLE `dload__ranksystem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__rights-tools`
--
ALTER TABLE `dload__rights-tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__roles`
--
ALTER TABLE `dload__roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT für Tabelle `dload__source_type`
--
ALTER TABLE `dload__source_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `dload__stories`
--
ALTER TABLE `dload__stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__stories-validate`
--
ALTER TABLE `dload__stories-validate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__user-login`
--
ALTER TABLE `dload__user-login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__user_browserinfo`
--
ALTER TABLE `dload__user_browserinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__user_requests`
--
ALTER TABLE `dload__user_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dload__visitors`
--
ALTER TABLE `dload__visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `dload__module-rank`
--
ALTER TABLE `dload__module-rank`
  ADD CONSTRAINT `aid-module-rank` FOREIGN KEY (`aid`) REFERENCES `dload__api-token` (`id`),
  ADD CONSTRAINT `rank-id-constraint` FOREIGN KEY (`rid`) REFERENCES `dload__ranks` (`id`);

--
-- Constraints der Tabelle `dload__pictures`
--
ALTER TABLE `dload__pictures`
  ADD CONSTRAINT `aid-picture` FOREIGN KEY (`aid`) REFERENCES `dload__api-token` (`id`),
  ADD CONSTRAINT `src-type-pic-constraint` FOREIGN KEY (`sourcetype`) REFERENCES `dload__source_type` (`id`),
  ADD CONSTRAINT `uid-picture` FOREIGN KEY (`uid`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__pictures-validate`
--
ALTER TABLE `dload__pictures-validate`
  ADD CONSTRAINT `validate-fk-picture-id` FOREIGN KEY (`picture-id`) REFERENCES `dload__pictures` (`id`),
  ADD CONSTRAINT `validate-fk-user-id` FOREIGN KEY (`user-id`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__ranksystem`
--
ALTER TABLE `dload__ranksystem`
  ADD CONSTRAINT `aid` FOREIGN KEY (`aid`) REFERENCES `dload__api-token` (`id`),
  ADD CONSTRAINT `oid` FOREIGN KEY (`oid`) REFERENCES `dload__point-origin` (`id`),
  ADD CONSTRAINT `uid` FOREIGN KEY (`uid`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__rights-tools`
--
ALTER TABLE `dload__rights-tools`
  ADD CONSTRAINT `AppID` FOREIGN KEY (`aid`) REFERENCES `dload__api-token` (`id`),
  ADD CONSTRAINT `RoleID` FOREIGN KEY (`role`) REFERENCES `dload__roles` (`id`),
  ADD CONSTRAINT `userID` FOREIGN KEY (`uid`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__stories`
--
ALTER TABLE `dload__stories`
  ADD CONSTRAINT `aid_stories` FOREIGN KEY (`aid`) REFERENCES `dload__api-token` (`id`),
  ADD CONSTRAINT `uid_stories` FOREIGN KEY (`user_id`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__stories-validate`
--
ALTER TABLE `dload__stories-validate`
  ADD CONSTRAINT `fk-sid-sid-val` FOREIGN KEY (`sid`) REFERENCES `dload__stories` (`id`),
  ADD CONSTRAINT `fk-uid-sid-val` FOREIGN KEY (`uid`) REFERENCES `dload__user-login` (`id`);

--
-- Constraints der Tabelle `dload__user-login`
--
ALTER TABLE `dload__user-login`
  ADD CONSTRAINT `role-id-verk` FOREIGN KEY (`role`) REFERENCES `dload__roles` (`id`);

--
-- Constraints der Tabelle `dload__user_requests`
--
ALTER TABLE `dload__user_requests`
  ADD CONSTRAINT `module-request-statistics` FOREIGN KEY (`module`) REFERENCES `dload__api-token` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
