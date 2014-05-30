<?php
$toID = '';
$status = '';
$firma = '';
$vorname = '';
$nachname = '';
$email = '';
$telefon = '';
$betreff = '';
$mitteilung = '';

$fehlerArr = array();

$toArr = array();
$toID = (isset($showPage -> arrVars[1])) ? $showPage -> arrVars[1] : 0;

$showPage -> pageArr['platzhalter']['title'] = 'BSVB kontaktieren';
$to = 'webmaster@bsv-buelach.ch';

$sql = "
SELECT
  vorname, nachname, email
  
FROM
  benutzer
  
WHERE
  ID='{p}'
";
$qry = $DB_LINK -> query($sql, array($toID));
if($qry -> num_rows() == 1) {
	$res = $qry -> fetch_assoc();
	$showPage -> pageArr['platzhalter']['title'] = "{$res['vorname']} {$res['nachname']} kontaktieren";
	$to = $res['email'];

}	

if(isset($_GET['send'])) {

  if(isset($_POST['firma'])) { $firma = $_POST['firma']; }
  if(isset($_POST['vorname'])) { $vorname = $_POST['vorname']; }
  if(isset($_POST['nachname'])) { $nachname = $_POST['nachname']; }
  if(isset($_POST['telefon'])) { $telefon = $_POST['telefon']; }

	if(!isset($_POST['email']) || $_POST['email'] == '') {
		$fehlerArr[] = "Es wurde keine E-Mail-Adresse eingegeben.";
	} elseif(!$showPage -> valemail($_POST['email'])) {
		$fehlerArr[] = "Es wurde eine ungültige E-Mail-Adresse eingegeben.";
	} else {
		$email = $_POST['email'];
	}

	if(!isset($_POST['betreff']) || $_POST['betreff'] == '') {
		$fehlerArr[] = 'Es wurde kein Betreff eingegeben.';
	} else {
		$betreff = $_POST['betreff'];
	}
	
	if(!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
		$fehlerArr[] = "Es wurde keine Mitteilung eingegeben.";
	} else {
		$mitteilung = $_POST['mitteilung'];
	}

	if(isset($_POST['subject']) && $_POST['subject'] != '') {
		$fehlerArr[] = "Bitte lassen Sie das Feld zur Spambekämpfung leer.";
	}
	
	if(count($fehlerArr) == 0) {
   	$from = $email;
    $fromName = $email;
    $subject = "Mitteilung über http://{$_SERVER['SERVER_NAME']}/kontakt-{$toID}.html";
    $addinfo = "no"; // or "yes"
    $text = "Diese Anfrage wurde über http://://{$_SERVER['SERVER_NAME']}/kontakt-{$toID}.html verschickt.\n\nFirma: {$firma}\nVorname: {$vorname}\nNachname: {$nachname}\nE-Mail: {$email}\nTelefon: {$telefon}\nMitteilung:\n\n{$mitteilung}";

    FormMailer::sendMail($to, $to, $from, $fromName, $subject, $text, "no", "txt");
//    FormMailer::sendMail("info@actra.ch", $to, $from, $fromName, $subject, $text, "no", "txt");

    $showPage -> redirect("kontaktRes-{$toID}.html");
  }
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><p><strong>Folgende Fehler sind aufgetreten:</strong></p><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['toID'] = $toID;
$platzhalter['status'] = $status;
$platzhalter['firma'] = $firma;
$platzhalter['vorname'] = $vorname;
$platzhalter['nachname'] = $nachname;
$platzhalter['email'] = $email;
$platzhalter['telefon'] = $telefon;
$platzhalter['mitteilung'] = $mitteilung;
$platzhalter['betreff'] = $betreff;
?>