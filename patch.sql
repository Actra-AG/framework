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

UPDATE benutzer SET lastlogin=null WHERE lastlogin='0000-00-00 00:00:00';
UPDATE benutzer SET confirmed=null WHERE confirmed='0000-00-00 00:00:00';
UPDATE benutzer SET accepted=null WHERE accepted='0000-00-00 00:00:00';
UPDATE benutzer SET denied=null WHERE denied='0000-00-00 00:00:00';

UPDATE benutzer SET email=RAND() WHERE email='';

UPDATE benutzer SET registered_by=0 WHERE registered_by NOT IN (SELECT ID FROM benutzer);
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