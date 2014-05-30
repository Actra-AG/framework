<?php
require_once("config.php");
$dbData = $config['dev']['DB'];
mysql_connect($dbData['hostname'], $dbData['username'], $dbData['password']);
mysql_select_db($dbData['database']);

/*
$sql = "SELECT vorname, email, benutzerID FROM benutzer WHERE deleted='0000-00-00 00:00:00' ORDER BY benutzerID";
$qry = mysql_query($sql);
while($res = mysql_fetch_assoc($qry)) {
  $to = $res['email'];
  $toName = "{$res['vorname']} {$res['nachname']}";
  $from = "contact@gladme.com";
  $fromName = "gladme.com";
  $subject = "Das neue gladme";
  $addinfo = "no"; // or "yes"
  $text = "Hallo {$res['vorname']}\n\nGood News! Pünktlich zur Weihnachtszeit bringen wir dir das neue gladme.\nViele neue und coole Features erwarten dich! Log dich auf www.gladme.com ein und lass dich von der Community überraschen!\n\nDie wichtigsten Neuerungen:\n\n++ Zeig deinen Style in der neuen Stylegallery – Bilder sagen ja angeblich mehr als tausend Worte...\n\n++ Mit dem Sammeltool erfüllst du zusammen mit deinen Freunden grosse Wünsche.\n\n++ Dank des Geburtstagskalenders vergisst du nie wieder einen Geburtstag. Neben den Freunden auf gladme kannst du auch weitere Personen erfassen.\n\n++ Es stehen dir neue Communitytools wie das Fotoalbum oder das Talkboard zur Verfügung.\n\nLiebe Grüsse\nDein gladme-Team\n\n\nPS: Überprüfe gleich die Aktualität deines Profils. Insbesondere die erweiterten Einstellungen zur Privatsphäre.";
  $formmailer -> sendMail($to, $toName, $from, $fromName, $subject, $text, "no", "txt");
  echo "{$res['benutzerID']}: OKAY<br />\n";
}
*/



// RENAME FOLDER gallery TO galerie
// Rechte von allen Fotos im galerie folder prüfen!

// RENAME galleryKat TO alben
// ALTER TABLE `alben` CHANGE `katID` `ID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT 
// ALTER TABLE `alben` CHANGE `titel` `titel` VARCHAR( 120 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL 
// ALTER TABLE `alben` ADD `pos` FLOAT( 9, 1 ) NOT NULL ;

/*
ALTER TABLE `benutzer` CHANGE `benutzerID` `ID` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT, CHANGE `registered` `registered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, CHANGE `lastlogin` `lastlogin` DATETIME NOT NULL, CHANGE `confirmed` `confirmed` DATETIME NOT NULL, CHANGE `deleted` `deleted` DATETIME NOT NULL, CHANGE `email` `email` VARCHAR(120) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `passwort` `passwort` CHAR(32) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `aktiv` `aktiv` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1', CHANGE `lizenz` `lizenz` INT UNSIGNED NOT NULL DEFAULT '0', CHANGE `nachname` `nachname` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `vorname` `vorname` VARCHAR(40) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `strasse` `strasse` VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `plz` `plz` VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL DEFAULT '0', CHANGE `ort` `ort` VARCHAR(60) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL, CHANGE `ehren` `ehren` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `frei` `frei` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `vorstand` `vorstand` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `admin` `admin` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `anzeigen` `anzeigen` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `redaktor` `redaktor` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `mw` `mw` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `js` `js` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `vt` `vt` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `gm` `gm` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `sa` `sa` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `jpvor` `jpvor` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `jpvers` `jpvers` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0', CHANGE `ernannt` `ernannt` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0', CHANGE `wronglogin` `wronglogin` TINYINT(3) UNSIGNED NOT NULL, CHANGE `visits` `visits` MEDIUMINT(8) UNSIGNED NOT NULL, CHANGE `ip` `ip` VARCHAR(80) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL
*/

// ALTER TABLE `benutzer` ADD `registered_by` MEDIUMINT UNSIGNED NOT NULL AFTER `ID` ;
// ALTER TABLE `benutzer` ADD `anrede` ENUM( 'Herr', 'Frau' ) NOT NULL AFTER `lizenz` ;

// ALTER TABLE `benutzer` ADD `telefon` VARCHAR( 60 ) NOT NULL AFTER `ort` , ADD `geburtsdatum` DATE NOT NULL AFTER `telefon` ;
// ALTER TABLE `dateiformate` CHANGE `ID` `ID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT 
// ALTER TABLE `dateiformate` DROP `deleted` 
// ALTER TABLE `dateiformate` CHANGE `gruppen` `arten` SET( 'bilder', 'dokumente' ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL 
// DELETE FROM benutzer WHERE deleted != '0000-00-00 00:00:00'
// ALTER TABLE `benutzer` DROP `deleted` 
// DROP TABLE `eidg07ranglisten` 

// RENAME galleryFotos TO fotos

/*
ALTER TABLE `fotos` CHANGE `fotoID` `ID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `katID` `albumID` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0',
CHANGE `text` `text` TEXT CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL 
*/

/*
ALTER TABLE `fotos` ADD `pos` FLOAT( 9, 1 ) NOT NULL AFTER `text` ,
ADD `typ` CHAR( 3 ) NOT NULL AFTER `pos` ;
*/

// ALTER TABLE `fotos` ADD `registered` DATETIME NOT NULL AFTER `albumID` ;
// UPDATE fotos SET registered=FROM_UNIXTIME(timestamp)
// UPDATE fotos SET registered = '0000-00-00 00:00:00' WHERE registered = '1970-01-01 01:00:00'
// DROP TABLE `sessions` 

//UPDATE fotos SET typ='jpg'

// ALTER TABLE `news` CHANGE `newsID` `ID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT 
// ALTER TABLE `news` ADD `registered_by` MEDIUMINT UNSIGNED NOT NULL AFTER `ID` ;
// ALTER TABLE `news` CHANGE `titel` `titel` VARCHAR( 120 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL 
// ALTER TABLE `news` CHANGE `archiv` `archiv` TINYINT UNSIGNED NOT NULL DEFAULT '0'
// ALTER TABLE `news` DROP `timestamp`

// ALTER TABLE `jahresprogramm` CHANGE `anlassID` `ID` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT 
// ALTER TABLE `jahresprogramm` ADD `registered_by` MEDIUMINT UNSIGNED NOT NULL AFTER `ID` ;

// ALTER TABLE `jahresprogramm` CHANGE `ort` `ort` VARCHAR( 120 ) CHARACTER SET latin1 COLLATE latin1_german1_ci NOT NULL 
// ALTER TABLE `jahresprogramm` ADD `vereinID` MEDIUMINT UNSIGNED NOT NULL AFTER `registered_by` ;
// ALTER TABLE `jahresprogramm` ADD `zeit` VARCHAR( 80 ) NOT NULL AFTER `datumBis` ;

// ALTER TABLE `jahresprogramm` ADD `registered` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `ID` , ADD `confirmed` DATETIME NOT NULL AFTER `registered` ;
// ALTER TABLE `jahresprogramm` ADD `denied` DATETIME NOT NULL AFTER `confirmed` ;

/*
ALTER TABLE `jahresprogramm` CHANGE `SF300` `SA300` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `SF50` `SA50` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `SF25` `SA25` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `SF10` `SA10` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0'
*/

// ALTER TABLE `jahresprogramm` ADD `WB` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `VS` ;

/*
$pos = 0;
$sql = "SELECT ID FROM fotos ORDER BY albumID, ID";
$qry = mysql_query($sql);
while($res = mysql_fetch_assoc($qry)) {
	$pos++;
	mysql_query("UPDATE fotos SET pos='{$pos}' WHERE ID='{$res['ID']}'");
}
*/

/*
$ID = 0;
$sql = "SELECT ID FROM benutzer ORDER BY ID";
$qry = mysql_query($sql);
while($res = mysql_fetch_assoc($qry)) {
	$ID++;
	mysql_query("UPDATE benutzer SET ID='{$ID}' WHERE ID='{$res['ID']}'");
}
*/
// ALTER TABLE `benutzer`  AUTO_INCREMENT =26

$sql = "SELECT ID FROM jahresprogramm ORDER BY ID";
$qry = mysql_query($sql);
while($res = mysql_fetch_assoc($qry)) {
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/files/einladungVers'.$res['ID'].'.pdf')) {
		mysql_query("INSERT INTO dokumente SET objekt='anlass', objektID='{$res['ID']}', titel='Einladung', dateiname='protokoll{$res['ID']}.pdf', type='application/pdf'");
		$ID = mysql_insert_id();
		rename($_SERVER['DOCUMENT_ROOT'].'/files/einladungVers'.$res['ID'].'.pdf', $_SERVER['DOCUMENT_ROOT'].'/dokumente/'.$ID.'.pdf') or die('error'.$_SERVER['DOCUMENT_ROOT'].'/dokumente/'.$ID.'.pdf');
	}
}



mysql_close();
echo 'OK';
?>