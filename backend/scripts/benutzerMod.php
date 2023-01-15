<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use framework\form\component\field\EmailField;
use framework\html\HtmlText;
use PDO;

class benutzerMod extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$status = '';
		$ID = 0;
		$vereine = '';
		$frau = '';
		$herr = '';
		$pwinfo = '';
		$opt1 = '';
		$opt2 = '';
		$opt3 = '';
		$opt4 = '';
		$opt5 = '';
		$jpwahl = '';

		$datenArr['vereinID'] = '';
		$datenArr['anrede'] = '';
		$datenArr['vorname'] = '';
		$datenArr['nachname'] = '';
		$datenArr['strasse'] = '';
		$datenArr['plz'] = '';
		$datenArr['ort'] = '';
		$datenArr['lizenz'] = '';
		$datenArr['telefon'] = '';
		$datenArr['email'] = '';
		$datenArr['geburtsdatum'] = '';
		$datenArr['bemerkungen'] = '';
		$datenArr['ehren'] = '';
		$datenArr['ernannt'] = '';
		$datenArr['passwort'] = '';
		$datenArr['aktiv'] = '';
		$datenArr['admin'] = '';
		$datenArr['redaktor'] = '';
		$datenArr['vorstand'] = '';

		$cjp = [];

		$fehlerArr = [];

		if ($this->showPage->checkUG('admin')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "
	SELECT
	  vereinID, anrede, vorname, nachname, strasse, plz, ort, lizenz, telefon, email, IF(geburtsdatum='0000-00-00', '', DATE_FORMAT(geburtsdatum, '%d.%m.%Y')) AS geburtsdatum, bemerkungen, ehren, ernannt, passwort, aktiv, admin, redaktor, vorstand
	  
	FROM
	  benutzer
	  
	WHERE
	  ID=?
	";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Benutzer bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
				$pwinfo = " <em>Passwortfelder leer lassen um aktuelles Passwort zu behalten</em>";

				$sql = "SELECT vereinID FROM benutzervereine WHERE benutzerID=?";
				$qry = $this->db->query($sql, [$ID]);
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
					$cjp[] = $res['vereinID'];
				}
			} else {
				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Benutzer hinzufügen';
			}

			$vArr = [];

			$vArr[0] = 'keiner';
			$sql = "SELECT ID, name FROM vereine ORDER BY name";
			$qry = $this->db->query($sql);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$vArr[$res['ID']] = $res['name'];
			}

			$optArr[0] = 'Nein';
			$optArr[1] = 'Ja';

			if (isset($_GET['send'])) {
				$addArr = [];
				$delArr = [];

				if (!isset($_POST['vereinID']) || !isset($vArr[$_POST['vereinID']])) {
					$fehlerArr[] = "Wählen Sie einen Verein aus";
				} else {
					$datenArr['vereinID'] = $_POST['vereinID'];
				}

				if (!isset($_POST['anrede'])) {
					$fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';
				} else if ($_POST['anrede'] != 'Herr' && $_POST['anrede'] != 'Frau') {
					$fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';
				} else {
					$datenArr['anrede'] = $_POST['anrede'];
				}

				if (!isset($_POST['vorname']) || trim($_POST['vorname']) == '') {
					$fehlerArr[] = "Geben Sie bitte Ihren Vornamen an.";
				} else {
					$datenArr['vorname'] = $_POST['vorname'];
				}

				if (!isset($_POST['nachname']) || trim($_POST['nachname']) == '') {
					$fehlerArr[] = "Geben Sie bitte Ihren Nachnamen an.";
				} else {
					$datenArr['nachname'] = $_POST['nachname'];
				}

				if (!isset($_POST['strasse']) || trim($_POST['strasse']) == '') {
					$fehlerArr[] = "Geben Sie bitte den Nachnamen an.";
				} else {
					$datenArr['strasse'] = $_POST['strasse'];
				}

				if (!isset($_POST['plz']) || trim($_POST['plz']) == '') {
					$fehlerArr[] = "Geben Sie bitte die PLZ an.";
				} else {
					$datenArr['plz'] = $_POST['plz'];
				}

				if (!isset($_POST['ort']) || trim($_POST['ort']) == '') {
					$fehlerArr[] = "Geben Sie bitte den Ort an.";
				} else {
					$datenArr['ort'] = $_POST['ort'];
				}

				if (isset($_POST['lizenz'])) {
					$datenArr['lizenz'] = $_POST['lizenz'];
				}
				if (isset($_POST['telefon'])) {
					$datenArr['telefon'] = $_POST['telefon'];
				}

				$emailField = new EmailField(
					name: 'email',
					label: HtmlText::encoded(textContent: 'E-Mail'),
					value: null,
					invalidError: HtmlText::encoded(textContent: 'Geben Sie bitte eine gültige E-Mail-Adresse an.'),
					requiredError: HtmlText::encoded(textContent: 'Geben Sie bitte eine E-Mail-Adresse an.')
				);
				if (!$emailField->validate(inputData: $_POST)) {
					$fehlerArr[] = $emailField->getErrorsAsHtmlTextObjects()[0]->render();
				} else {
					$datenArr['email'] = $emailField->getRawValue();
					$sql = "SELECT COUNT(*) AS anz FROM benutzer WHERE email=? AND ID!=?";
					$qry = $this->db->query($sql, [$datenArr['email'], $ID]);
					$res = $qry->fetch(PDO::FETCH_ASSOC);
					if ($res['anz'] != 0) {
						$fehlerArr[] = 'Die eingegebene E-Mail-Adresse ist bereits registriert. Geben Sie bitte eine andere ein.';
					}
				}
				if (!isset($_POST['geburtsdatum']) || $_POST['geburtsdatum'] == '') {
					$datenArr['geburtsdatum'] = '';
				} else if (!preg_match("/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/", $_POST['geburtsdatum'])) {
					$fehlerArr[] = 'Sie haben ein ungültiges Geburtsdatum eingegeben.';
					$datenArr['geburtsdatum'] = $_POST['geburtsdatum'];
				} else {
					$datenArr['geburtsdatum'] = $_POST['geburtsdatum'];
				}

				if (isset($_POST['bemerkungen'])) {
					$datenArr['bemerkungen'] = $_POST['bemerkungen'];
				}

				if (!isset($_POST['ehren']) || !array_key_exists($_POST['ehren'], $optArr)) {
					$fehlerArr[] = 'Sie haben bei "Ehrenmitglied" eine ungültige Auswahl getroffen.';
				} else {
					$datenArr['ehren'] = $_POST['ehren'];
				}

				if (isset($_POST['ernannt'])) {
					$datenArr['ernannt'] = $_POST['ernannt'];
				}

				if (!isset($_POST['passwort']) || $_POST['passwort'] == '') {
					//    	$datenArr['passwort'] = '';

				} else if (!isset($_POST['passwort2']) || $_POST['passwort2'] == '' || $_POST['passwort'] != $_POST['passwort2']) {
					$fehlerArr[] = 'Sie haben nicht zweimal dasselbe Passwort eingegeben.';
				} else {
					$datenArr['passwort'] = md5($_POST['passwort']);
				}

				if (!isset($_POST['aktiv']) || !array_key_exists($_POST['aktiv'], $optArr)) {
					$fehlerArr[] = 'Sie haben bei "Zugang Aktiv?" eine ungültige Auswahl getroffen.';
				} else {
					$datenArr['aktiv'] = $_POST['aktiv'];
				}

				if (!isset($_POST['admin']) || !array_key_exists($_POST['admin'], $optArr)) {
					$fehlerArr[] = 'Sie haben bei "Administrator" eine ungültige Auswahl getroffen.';
				} else {
					$datenArr['admin'] = $_POST['admin'];
				}

				if (!isset($_POST['redaktor']) || !array_key_exists($_POST['redaktor'], $optArr)) {
					$fehlerArr[] = 'Sie haben bei "Redaktor" eine ungültige Auswahl getroffen.';
				} else {
					$datenArr['redaktor'] = $_POST['redaktor'];
				}

				if (!isset($_POST['vorstand']) || !array_key_exists($_POST['vorstand'], $optArr)) {
					$fehlerArr[] = 'Sie haben bei "Vorstand" eine ungültige Auswahl getroffen.';
				} else {
					$datenArr['vorstand'] = $_POST['vorstand'];
				}

				foreach ($cjp as $key => $vID) {
					if (!isset($_POST['vwahl']) || !in_array($vID, $_POST['vwahl'])) {
						$delArr[] = $vID;
						unset($cjp[$key]);
					}
				}

				if (isset($_POST['vwahl'])) {
					foreach ($_POST['vwahl'] as $vID) {
						if (!in_array($vID, $cjp)) {
							$addArr[] = $vID;
							$cjp[] = $vID;
						}
					}
				}

				if (count($fehlerArr) == 0) {

					$datenArr['ID'] = $ID;
					if ($datenArr['geburtsdatum'] != '') {
						$gebArr = explode(".", $datenArr['geburtsdatum']);
						$datenArr['geburtsdatum'] = "{$gebArr[2]}-{$gebArr[1]}-{$gebArr[0]}";
					}

					if ($ID == 0) {
						$datenArr['registered_by'] = $this->showPage->userData->ID;
						$ID = $bsvb->insertEntry('benutzer', $datenArr);
						$this->db->query("UPDATE benutzer SET confirmed=NOW() WHERE ID=?", [$ID]);
					} else {
						$bsvb->updateEntry('benutzer', $ID, $datenArr);
					}

					foreach ($delArr as $vID) {
						$this->db->query("DELETE FROM benutzervereine WHERE benutzerID=? AND vereinID=?", [$ID, $vID]);
					}

					foreach ($addArr as $vID) {
						$this->db->query("INSERT INTO benutzervereine SET benutzerID=?, vereinID=?", [$ID, $vID]);
					}

					$this->showPage->redirect("benutzerDet-{$ID}.html?ac={$ac}");
				}
			}

			foreach ($vArr as $key => $val) {
				$vereine .= "<option value=\"{$key}\"";
				if ($key == $datenArr['vereinID']) {
					$vereine .= ' selected="selected"';
				}
				$vereine .= ">{$val}</option>\n";

				if ($key != 0) {
					$jpwahl .= "<li><input type=\"checkbox\" name=\"vwahl[]\" value=\"{$key}\" id=\"vwahl{$key}\"";
					if (in_array($key, $cjp)) {
						$jpwahl .= ' checked="checked"';
					}
					$jpwahl .= "/> <label for=\"vwahl{$key}\">{$val}</label></li>\n";
				}
			}

			if ($datenArr['anrede'] == 'Frau') {
				$frau = ' checked="checked"';
			}
			if ($datenArr['anrede'] == 'Herr') {
				$herr = ' checked="checked"';
			}

			foreach ($optArr as $key => $val) {
				$opt1 .= "<option value=\"{$key}\"";
				if ($key == $datenArr['aktiv']) {
					$opt1 .= ' selected="selected"';
				}
				$opt1 .= ">{$val}</option>\n";

				$opt2 .= "<option value=\"{$key}\"";
				if ($key == $datenArr['admin']) {
					$opt2 .= ' selected="selected"';
				}
				$opt2 .= ">{$val}</option>\n";

				$opt3 .= "<option value=\"{$key}\"";
				if ($key == $datenArr['redaktor']) {
					$opt3 .= ' selected="selected"';
				}
				$opt3 .= ">{$val}</option>\n";

				$opt4 .= "<option value=\"{$key}\"";
				if ($key == $datenArr['vorstand']) {
					$opt4 .= ' selected="selected"';
				}
				$opt4 .= ">{$val}</option>\n";

				$opt5 .= "<option value=\"{$key}\"";
				if ($key == $datenArr['ehren']) {
					$opt5 .= ' selected="selected"';
				}
				$opt5 .= ">{$val}</option>\n";
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $key => $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['vereine'] = $vereine;
		$this->placeholders['frau'] = $frau;
		$this->placeholders['herr'] = $herr;
		$this->placeholders['pwinfo'] = $pwinfo;
		$this->placeholders['opt1'] = $opt1;
		$this->placeholders['opt2'] = $opt2;
		$this->placeholders['opt3'] = $opt3;
		$this->placeholders['opt4'] = $opt4;
		$this->placeholders['opt5'] = $opt5;
		$this->placeholders['jpwahl'] = $jpwahl;

		foreach ($datenArr as $key => $val) {
			$this->placeholders[$key] = $val;
		}
	}
}


/* EOF */