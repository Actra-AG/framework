-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Erstellungszeit: 15. Feb 2026 um 19:41
-- Server-Version: 10.11.15-MariaDB-ubu2204-log
-- PHP-Version: 8.3.26

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `db`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `alben`
--

CREATE TABLE `alben` (
                         `ID` mediumint(8) UNSIGNED NOT NULL,
                         `titel` varchar(120) NOT NULL,
                         `typ` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
                         `pos` float(9,1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzer`
--

CREATE TABLE `benutzer` (
                            `ID` mediumint(8) UNSIGNED NOT NULL,
                            `vereinID` mediumint(8) UNSIGNED NOT NULL,
                            `registered_by` mediumint(8) UNSIGNED NOT NULL,
                            `registered` timestamp NOT NULL DEFAULT current_timestamp(),
                            `lastlogin` datetime DEFAULT NULL,
                            `confirmed` datetime DEFAULT NULL,
                            `accepted` datetime DEFAULT NULL,
                            `denied` datetime DEFAULT NULL,
                            `email` varchar(120) NOT NULL,
                            `passwort` char(32) NOT NULL,
                            `aktiv` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
                            `lizenz` int(10) UNSIGNED NOT NULL DEFAULT 0,
                            `anrede` enum('Herr','Frau') NOT NULL,
                            `nachname` varchar(40) NOT NULL,
                            `vorname` varchar(40) NOT NULL,
                            `strasse` varchar(60) NOT NULL,
                            `plz` varchar(20) NOT NULL DEFAULT '0',
                            `ort` varchar(60) NOT NULL,
                            `telefon` varchar(60) NOT NULL,
                            `kommentar` longtext NOT NULL,
                            `bemerkungen` longtext NOT NULL,
                            `geburtsdatum` date NOT NULL,
                            `ehren` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                            `vorstand` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                            `admin` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                            `redaktor` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                            `ernannt` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
                            `wronglogin` tinyint(3) UNSIGNED NOT NULL,
                            `visits` mediumint(8) UNSIGNED NOT NULL,
                            `ip` varchar(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `benutzervereine`
--

CREATE TABLE `benutzervereine` (
                                   `benutzerID` mediumint(8) UNSIGNED NOT NULL,
                                   `vereinID` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dateiformate`
--

CREATE TABLE `dateiformate` (
                                `ID` smallint(5) UNSIGNED NOT NULL,
                                `mimetype` varchar(120) NOT NULL,
                                `extension` varchar(10) NOT NULL,
                                `arten` set('foto','dokumente') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dokumente`
--

CREATE TABLE `dokumente` (
                             `ID` mediumint(8) UNSIGNED NOT NULL,
                             `objekt` varchar(40) NOT NULL,
                             `objektID` mediumint(8) UNSIGNED NOT NULL,
                             `titel` varchar(120) NOT NULL,
                             `dateiname` varchar(180) NOT NULL,
                             `type` varchar(120) NOT NULL,
                             `views` mediumint(8) UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fotos`
--

CREATE TABLE `fotos` (
                         `ID` mediumint(8) UNSIGNED NOT NULL,
                         `albumID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
                         `registered` datetime NOT NULL,
                         `text` longtext NOT NULL,
                         `pos` float(9,1) NOT NULL,
                         `typ` char(3) NOT NULL,
                         `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `jahresprogramm`
--

CREATE TABLE `jahresprogramm` (
                                  `ID` mediumint(8) UNSIGNED NOT NULL,
                                  `registered` timestamp NOT NULL DEFAULT current_timestamp(),
                                  `lastmod` datetime NOT NULL,
                                  `confirmed` datetime DEFAULT NULL,
                                  `denied` datetime DEFAULT NULL,
                                  `registered_by` mediumint(8) UNSIGNED NOT NULL,
                                  `vereinID` mediumint(8) UNSIGNED NOT NULL,
                                  `datumVon` date NOT NULL DEFAULT '0000-00-00',
                                  `datumBis` date NOT NULL DEFAULT '0000-00-00',
                                  `zeit` varchar(80) NOT NULL,
                                  `titel` varchar(120) NOT NULL DEFAULT '',
                                  `ort` varchar(120) NOT NULL,
                                  `bemerkungen` longtext NOT NULL,
                                  `gm300` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `gm50` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `gm25` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `gm10` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `mw300` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `mw50` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `mwlg` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `mwlp` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `mwba` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `js` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `vt` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `sa300` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `sa50` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `sa25` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `sa10` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `vs` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `wb` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `vorstand` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                                  `export` tinyint(3) UNSIGNED NOT NULL,
                                  `zeitVon` time NOT NULL,
                                  `zeitBis` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
                        `ID` mediumint(8) UNSIGNED NOT NULL,
                        `registered_by` mediumint(8) UNSIGNED NOT NULL,
                        `datum` date NOT NULL DEFAULT '0000-00-00',
                        `titel` varchar(120) NOT NULL,
                        `teaser` longtext NOT NULL,
                        `text` longtext NOT NULL,
                        `archiv` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
                        `typ` tinyint(3) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `resultate`
--

CREATE TABLE `resultate` (
                             `resultatID` mediumint(8) UNSIGNED NOT NULL,
                             `anlassID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
                             `schuetzeID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
                             `gruppenID` mediumint(8) UNSIGNED NOT NULL DEFAULT 0,
                             `punkte` mediumint(8) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `seiteninhalte`
--

CREATE TABLE `seiteninhalte` (
                                 `ID` mediumint(8) UNSIGNED NOT NULL,
                                 `benutzerID` mediumint(8) UNSIGNED NOT NULL,
                                 `datum` timestamp NOT NULL DEFAULT current_timestamp(),
                                 `ort` enum('frontend','backend') NOT NULL,
                                 `seite` varchar(200) NOT NULL,
                                 `inhalt` longtext NOT NULL,
                                 `config` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vereine`
--

CREATE TABLE `vereine` (
                           `ID` mediumint(8) UNSIGNED NOT NULL,
                           `registered_by` mediumint(8) UNSIGNED NOT NULL,
                           `name` varchar(120) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `visits`
--

CREATE TABLE `visits` (
                          `benutzerID` mediumint(8) UNSIGNED NOT NULL,
                          `datum` timestamp NOT NULL DEFAULT current_timestamp(),
                          `sessionID` varchar(80) NOT NULL,
                          `ip` varchar(80) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `alben`
--
ALTER TABLE `alben`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_typ` (`typ`);

--
-- Indizes für die Tabelle `benutzer`
--
ALTER TABLE `benutzer`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_vereinID` (`vereinID`);

--
-- Indizes für die Tabelle `benutzervereine`
--
ALTER TABLE `benutzervereine`
    ADD PRIMARY KEY (`benutzerID`,`vereinID`);

--
-- Indizes für die Tabelle `dateiformate`
--
ALTER TABLE `dateiformate`
    ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `dokumente`
--
ALTER TABLE `dokumente`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_objekt` (`objekt`,`objektID`);

--
-- Indizes für die Tabelle `fotos`
--
ALTER TABLE `fotos`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_albumID` (`albumID`);

--
-- Indizes für die Tabelle `jahresprogramm`
--
ALTER TABLE `jahresprogramm`
    ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_typ` (`typ`);

--
-- Indizes für die Tabelle `resultate`
--
ALTER TABLE `resultate`
    ADD PRIMARY KEY (`resultatID`),
    ADD KEY `idx_anlassID` (`anlassID`),
    ADD KEY `idx_schuetzeID` (`schuetzeID`),
    ADD KEY `idx_gruppeID` (`gruppenID`);

--
-- Indizes für die Tabelle `seiteninhalte`
--
ALTER TABLE `seiteninhalte`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `idx_sprache` (`ort`),
    ADD KEY `idx_benutzerID` (`benutzerID`);

--
-- Indizes für die Tabelle `vereine`
--
ALTER TABLE `vereine`
    ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `visits`
--
ALTER TABLE `visits`
    ADD KEY `idx_benutzerID` (`benutzerID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `alben`
--
ALTER TABLE `alben`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `benutzer`
--
ALTER TABLE `benutzer`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dateiformate`
--
ALTER TABLE `dateiformate`
    MODIFY `ID` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `dokumente`
--
ALTER TABLE `dokumente`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `fotos`
--
ALTER TABLE `fotos`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `jahresprogramm`
--
ALTER TABLE `jahresprogramm`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `resultate`
--
ALTER TABLE `resultate`
    MODIFY `resultatID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `seiteninhalte`
--
ALTER TABLE `seiteninhalte`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `vereine`
--
ALTER TABLE `vereine`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;