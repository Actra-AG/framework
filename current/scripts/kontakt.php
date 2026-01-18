<?php

namespace scripts;

use classes\pageClass;
use PDO;
use classes\FormMailer;

class kontakt extends pageClass
{
	public function execute()
	{
		$status = '';
		$firma = '';
		$vorname = '';
		$nachname = '';
		$email = '';
		$telefon = '';
		$betreff = '';
		$mitteilung = '';

		$fehlerArr = [];

		$toID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

		$this->showPage->pageArr['platzhalter']['title'] = 'BSVB kontaktieren';
		$to = 'webmaster@bsv-buelach.ch';

		$sql = "
SELECT
  vorname, nachname, email
  
FROM
  benutzer
  
WHERE
  ID=?
";
		$qry = $this->db->prepareAndExecute($sql, [$toID]);
		if ($qry->rowCount() == 1) {
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			$this->showPage->pageArr['platzhalter']['title'] = "{$res['vorname']} {$res['nachname']} kontaktieren";
			$to = $res['email'];
		}

		if (isset($_GET['send'])) {

			if (isset($_POST['firma'])) {
				$firma = $_POST['firma'];
			}
			if (isset($_POST['vorname'])) {
				$vorname = $_POST['vorname'];
			}
			if (isset($_POST['nachname'])) {
				$nachname = $_POST['nachname'];
			}
			if (isset($_POST['telefon'])) {
				$telefon = $_POST['telefon'];
			}

			if (!isset($_POST['email']) || $_POST['email'] == '') {
				$fehlerArr[] = "Es wurde keine E-Mail-Adresse eingegeben.";
			} else if (!$this->showPage->valemail($_POST['email'])) {
				$fehlerArr[] = "Es wurde eine ungültige E-Mail-Adresse eingegeben.";
			} else {
				$email = $_POST['email'];
			}

			if (!isset($_POST['betreff']) || $_POST['betreff'] == '') {
				$fehlerArr[] = 'Es wurde kein Betreff eingegeben.';
			} else {
				$betreff = $_POST['betreff'];
			}

			if (!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
				$fehlerArr[] = "Es wurde keine Mitteilung eingegeben.";
			} else {
				$mitteilung = $_POST['mitteilung'];
			}

			if (isset($_POST['subject']) && $_POST['subject'] != '') {
				$fehlerArr[] = "Bitte lassen Sie das Feld zur Spambekämpfung leer.";
			}

			if (count($fehlerArr) == 0) {
				$from = $email;
				$fromName = $email;
				$subject = "Mitteilung über http://{$_SERVER['SERVER_NAME']}/kontakt-{$toID}.html";
				$text = "Diese Anfrage wurde über http://://{$_SERVER['SERVER_NAME']}/kontakt-{$toID}.html verschickt.\n\nFirma: {$firma}\nVorname: {$vorname}\nNachname: {$nachname}\nE-Mail: {$email}\nTelefon: {$telefon}\nMitteilung:\n\n{$mitteilung}";

				(new FormMailer())->send($to, $to, $from, $fromName, $subject, $text);
				$this->showPage->redirect("kontaktRes-{$toID}.html");
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><p><strong>Folgende Fehler sind aufgetreten:</strong></p><ul>\n";
			foreach ($fehlerArr as $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['toID'] = $toID;
		$this->placeholders['status'] = $status;
		$this->placeholders['firma'] = $firma;
		$this->placeholders['vorname'] = $vorname;
		$this->placeholders['nachname'] = $nachname;
		$this->placeholders['email'] = $email;
		$this->placeholders['telefon'] = $telefon;
		$this->placeholders['mitteilung'] = $mitteilung;
		$this->placeholders['betreff'] = $betreff;
	}
}