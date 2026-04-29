# Globally change charset to utf8mb4_unicode_ci
# Patch zum Löschen aller Dokumente im Dateisystem die in der Datenbank fehlen!!!

ALTER TABLE alben ENGINE=InnoDB;
ALTER TABLE benutzer ENGINE=InnoDB;
ALTER TABLE benutzervereine ENGINE=InnoDB;
ALTER TABLE dateiformate ENGINE=InnoDB;
ALTER TABLE dokumente ENGINE=InnoDB;
ALTER TABLE fotos ENGINE=InnoDB;
ALTER TABLE jahresprogramm ENGINE=InnoDB;
ALTER TABLE news ENGINE=InnoDB;
ALTER TABLE resultate ENGINE=InnoDB;
ALTER TABLE seiteninhalte ENGINE=InnoDB;
ALTER TABLE vereine ENGINE=InnoDB;
ALTER TABLE visits ENGINE=InnoDB;

ALTER TABLE `benutzer` CHANGE `lastlogin` `lastlogin` DATETIME NULL, CHANGE `confirmed` `confirmed` DATETIME NULL;
ALTER TABLE `benutzer` CHANGE `accepted` `accepted` DATETIME NULL, CHANGE `denied` `denied` DATETIME NULL;
UPDATE benutzer SET lastlogin=null WHERE lastlogin='0000-00-00 00:00:00';
UPDATE benutzer SET confirmed=null WHERE confirmed='0000-00-00 00:00:00';
UPDATE benutzer SET accepted=null WHERE accepted='0000-00-00 00:00:00';
UPDATE benutzer SET denied=null WHERE denied='0000-00-00 00:00:00';

UPDATE benutzer SET email=RAND() WHERE email='';
UPDATE benutzer SET registered_by=0 WHERE registered_by NOT IN (SELECT ID FROM benutzer);

CREATE TABLE `auth_group` (
                              `ID` mediumint(8) UNSIGNED NOT NULL,
                              `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_group_right`
--

CREATE TABLE `auth_group_right` (
                                    `ID` mediumint(8) UNSIGNED NOT NULL,
                                    `groupID` mediumint(8) UNSIGNED NOT NULL,
                                    `rightName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_ipWhitelist`
--

CREATE TABLE `auth_ipWhitelist` (
                                    `ID` mediumint(8) UNSIGNED NOT NULL,
                                    `userID` mediumint(8) UNSIGNED NOT NULL,
                                    `ipAddress` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_login`
--

CREATE TABLE `auth_login` (
                              `ID` mediumint(8) UNSIGNED NOT NULL,
                              `userID` mediumint(8) UNSIGNED DEFAULT NULL,
                              `registered` timestamp NOT NULL DEFAULT current_timestamp(),
                              `sessionId` varchar(200) NOT NULL,
                              `ipAddress` varchar(200) NOT NULL,
                              `email` varchar(200) NOT NULL,
                              `result` tinyint(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_right`
--

CREATE TABLE `auth_right` (
                              `name` varchar(200) NOT NULL,
                              `title` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_session`
--

CREATE TABLE `auth_session` (
                                `ID` mediumint(8) UNSIGNED NOT NULL,
                                `parentID` mediumint(8) UNSIGNED DEFAULT NULL,
                                `userID` mediumint(8) UNSIGNED NOT NULL,
                                `lastAction` datetime DEFAULT NULL,
                                `sessionId` varchar(200) NOT NULL,
                                `ipAddress` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_token`
--

CREATE TABLE `auth_token` (
                              `ID` mediumint(8) UNSIGNED NOT NULL,
                              `userID` mediumint(8) UNSIGNED NOT NULL,
                              `registered` timestamp NOT NULL DEFAULT current_timestamp(),
                              `registeredClient` text NOT NULL,
                              `type` varchar(200) NOT NULL,
                              `claimed` datetime DEFAULT NULL,
                              `claimedClient` text DEFAULT NULL,
                              `token` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_user`
--

CREATE TABLE `auth_user` (
                             `ID` mediumint(8) UNSIGNED NOT NULL,
                             `registeredByID` mediumint(8) UNSIGNED DEFAULT NULL,
                             `registered` timestamp NOT NULL DEFAULT current_timestamp(),
                             `invited` datetime DEFAULT NULL,
                             `email` varchar(200) NOT NULL,
                             `firstName` varchar(200) NOT NULL,
                             `lastName` varchar(200) NOT NULL,
                             `active` tinyint(3) UNSIGNED NOT NULL,
                             `lastSuccessfulLogin` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `auth_user_group`
--

CREATE TABLE `auth_user_group` (
                                   `ID` mediumint(8) UNSIGNED NOT NULL,
                                   `userID` mediumint(8) UNSIGNED NOT NULL,
                                   `groupID` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `auth_group`
--
ALTER TABLE `auth_group`
    ADD PRIMARY KEY (`ID`);

--
-- Indizes für die Tabelle `auth_group_right`
--
ALTER TABLE `auth_group_right`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `groupID` (`groupID`),
    ADD KEY `rightName` (`rightName`);

--
-- Indizes für die Tabelle `auth_ipWhitelist`
--
ALTER TABLE `auth_ipWhitelist`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `userID` (`userID`);

--
-- Indizes für die Tabelle `auth_login`
--
ALTER TABLE `auth_login`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `userID` (`userID`),
    ADD KEY `registered` (`registered`);

--
-- Indizes für die Tabelle `auth_right`
--
ALTER TABLE `auth_right`
    ADD PRIMARY KEY (`name`);

--
-- Indizes für die Tabelle `auth_session`
--
ALTER TABLE `auth_session`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `parentID` (`parentID`),
    ADD KEY `userID` (`userID`);

--
-- Indizes für die Tabelle `auth_token`
--
ALTER TABLE `auth_token`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `token` (`token`),
    ADD KEY `userID` (`userID`);

--
-- Indizes für die Tabelle `auth_user`
--
ALTER TABLE `auth_user`
    ADD PRIMARY KEY (`ID`),
    ADD UNIQUE KEY `email` (`email`),
    ADD KEY `registeredByID` (`registeredByID`);

--
-- Indizes für die Tabelle `auth_user_group`
--
ALTER TABLE `auth_user_group`
    ADD PRIMARY KEY (`ID`),
    ADD KEY `userID` (`userID`),
    ADD KEY `groupID` (`groupID`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `auth_group`
--
ALTER TABLE `auth_group`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_group_right`
--
ALTER TABLE `auth_group_right`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_ipWhitelist`
--
ALTER TABLE `auth_ipWhitelist`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_login`
--
ALTER TABLE `auth_login`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_session`
--
ALTER TABLE `auth_session`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_token`
--
ALTER TABLE `auth_token`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_user`
--
ALTER TABLE `auth_user`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `auth_user_group`
--
ALTER TABLE `auth_user_group`
    MODIFY `ID` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `auth_group_right`
--
ALTER TABLE `auth_group_right`
    ADD CONSTRAINT `auth_group_right_ibfk_1` FOREIGN KEY (`groupID`) REFERENCES `auth_group` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `auth_group_right_ibfk_2` FOREIGN KEY (`rightName`) REFERENCES `auth_right` (`name`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `auth_login`
--
ALTER TABLE `auth_login`
    ADD CONSTRAINT `auth_login_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `auth_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `auth_session`
--
ALTER TABLE `auth_session`
    ADD CONSTRAINT `auth_session_ibfk_1` FOREIGN KEY (`parentID`) REFERENCES `auth_session` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `auth_session_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `auth_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `auth_token`
--
ALTER TABLE `auth_token`
    ADD CONSTRAINT `auth_token_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `auth_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `auth_user`
--
ALTER TABLE `auth_user`
    ADD CONSTRAINT `auth_user_ibfk_1` FOREIGN KEY (`registeredByID`) REFERENCES `auth_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `auth_user_group`
--
ALTER TABLE `auth_user_group`
    ADD CONSTRAINT `auth_user_group_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `auth_user` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    ADD CONSTRAINT `auth_user_group_ibfk_2` FOREIGN KEY (`groupID`) REFERENCES `auth_group` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

INSERT INTO auth_user (ID, registeredByID, registered, invited, email, firstName, lastName, active, lastSuccessfulLogin)
SELECT ID,
       IF(registered_by=0, null, registered_by),
       registered,
       registered,
       email,
       vorname,
       nachname,
       aktiv,
       lastlogin
FROM benutzer
WHERE (vorstand=1 OR admin=1 OR redaktor=1);

INSERT INTO `auth_group` (`ID`, `title`) VALUES
                                             (1, 'Administrator'),
                                             (2, 'Vorstand'),
                                             (3, 'Redaktor'),
                                             (4, 'Mitglied');

INSERT INTO `auth_right` (`name`, `title`) VALUES
                                               ('backend_access', 'Zugriff ins Backend'),
                                               ('board_member', 'Vorstand'),
                                               ('editor', 'Redaktor'),
                                               ('manage_users', 'Benutzer verwalten');

INSERT INTO `auth_group_right` (`ID`, `groupID`, `rightName`) VALUES
                                                                  (1, 1, 'manage_users'),
                                                                  (2, 1, 'backend_access'),
                                                                  (4, 1, 'board_member'),
                                                                  (5, 1, 'editor'),
                                                                  (6, 2, 'backend_access'),
                                                                  (7, 2, 'board_member'),
                                                                  (8, 3, 'backend_access'),
                                                                  (9, 3, 'editor'),
                                                                  (10, 4, 'backend_access');

INSERT INTO auth_user_group (userID, groupID)
SELECT ID, 1
FROM benutzer
WHERE admin=1;

INSERT INTO auth_user_group (userID, groupID)
SELECT ID, 2
FROM benutzer
WHERE vorstand=1;

INSERT INTO auth_user_group (userID, groupID)
SELECT ID, 3
FROM benutzer
WHERE redaktor=1;

INSERT INTO auth_user (ID, registeredByID, registered, invited, email, firstName, lastName, active, lastSuccessfulLogin)
SELECT ID,
       IF(registered_by=0, null, registered_by),
       registered,
       registered,
       email,
       vorname,
       nachname,
       aktiv,
       lastlogin
FROM benutzer
WHERE (vorstand=0 AND admin=0 AND redaktor=0);

INSERT INTO auth_user_group (userID, groupID)
SELECT ID, 4
FROM benutzer
WHERE redaktor=0 AND vorstand=0 AND admin=0;

UPDATE auth_user
SET active=0
WHERE ID IN (SELECT ID FROM benutzer WHERE confirmed IS NULL OR denied IS NOT NULL);
ALTER TABLE `benutzer` CHANGE `ID` `ID` MEDIUMINT(8) UNSIGNED NOT NULL;

ALTER TABLE `benutzer` ADD FOREIGN KEY (`ID`) REFERENCES `auth_user`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `benutzer`
    DROP `email`,
    DROP `aktiv`,
    DROP `nachname`,
    DROP `vorname`;
ALTER TABLE `benutzer` DROP `confirmed`;
ALTER TABLE `benutzer` DROP `passwort`;
ALTER TABLE `benutzer`
    DROP `vorstand`,
    DROP `admin`,
    DROP `redaktor`,
    DROP `wronglogin`,
    DROP `visits`,
    DROP `ip`;

ALTER TABLE `benutzer`
    DROP `registered`,
    DROP `lastlogin`;

ALTER TABLE `jahresprogramm` CHANGE `vereinID` `vereinID` MEDIUMINT(8) UNSIGNED NULL;
UPDATE jahresprogramm SET vereinID=NULL WHERE vereinID=0;
ALTER TABLE `jahresprogramm` ADD FOREIGN KEY (`vereinID`) REFERENCES `vereine`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

CREATE TABLE `db`.`eventCategory` (`ID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT , `eventID` MEDIUMINT UNSIGNED NOT NULL , `categoryName` VARCHAR(200) NOT NULL , PRIMARY KEY (`ID`), INDEX (`eventID`), INDEX (`categoryName`)) ENGINE = InnoDB;
ALTER TABLE `eventCategory` ADD FOREIGN KEY (`eventID`) REFERENCES `jahresprogramm`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;

INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'gm300' FROM jahresprogramm WHERE gm300=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'gm50' FROM jahresprogramm WHERE gm50=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'gm25' FROM jahresprogramm WHERE gm25=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'gm10' FROM jahresprogramm WHERE gm10=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'mw50' FROM jahresprogramm WHERE mw50=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'mwlg' FROM jahresprogramm WHERE mwlg=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'mwlp' FROM jahresprogramm WHERE mwlp=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'js' FROM jahresprogramm WHERE js=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'vt' FROM jahresprogramm WHERE vt=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'sa300' FROM jahresprogramm WHERE sa300=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'sa50' FROM jahresprogramm WHERE sa50=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'sa25' FROM jahresprogramm WHERE sa25=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'sa10' FROM jahresprogramm WHERE sa10=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'vs' FROM jahresprogramm WHERE vs=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'wb' FROM jahresprogramm WHERE wb=1;
INSERT INTO eventCategory (eventID, categoryName) SELECT ID, 'vorstand' FROM jahresprogramm WHERE vorstand=1;

ALTER TABLE `jahresprogramm`
    DROP `gm300`,
    DROP `gm50`,
    DROP `gm25`,
    DROP `gm10`,
    DROP `mw300`,
    DROP `mw50`,
    DROP `mwlg`,
    DROP `mwlp`,
    DROP `mwba`,
    DROP `js`,
    DROP `vt`,
    DROP `sa300`,
    DROP `sa50`,
    DROP `sa25`,
    DROP `sa10`,
    DROP `vs`,
    DROP `wb`,
    DROP `vorstand`;

DELETE FROM dokumente WHERE objektID NOT IN (SELECT ID FROM jahresprogramm);
ALTER TABLE `dokumente` ADD FOREIGN KEY (`objektID`) REFERENCES `jahresprogramm`(`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `benutzer` CHANGE `geburtsdatum` `geburtsdatum` DATE NULL;
UPDATE benutzer SET geburtsdatum=NULL WHERE geburtsdatum='0000-00-00';
ALTER TABLE `news` CHANGE `datum` `datum` DATE NOT NULL;
ALTER TABLE `visits` CHANGE `benutzerID` `benutzerID` MEDIUMINT(8) UNSIGNED NULL;
UPDATE visits SET benutzerID=null WHERE benutzerID NOT IN (SELECT ID FROM benutzer);
INSERT INTO auth_login (userID, registered, sessionId, ipAddress, email, result)
SELECT benutzerID, datum, sessionID, ip, '', 1
FROM visits
ORDER BY datum;
DROP TABLE `alben`;
DROP TABLE `fotos`;
DROP TABLE `visits`;