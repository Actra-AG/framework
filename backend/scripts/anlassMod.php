<?php
$bsvb = new bsvb();

$status = '';
$ID = 0;
$vereine = '';
$kategorien = '';

$datenArr['vereinID'] = '';
$datenArr['datumVon'] = '';
$datenArr['datumBis'] = '';
$datenArr['zeitVon'] = '';
$datenArr['zeitBis'] = '';
$datenArr['zeit'] = '';
$datenArr['titel'] = '';
$datenArr['ort'] = '';
$datenArr['bemerkungen'] = '';
$datenArr['export'] = 0;

$jpArr = $bsvb->getJahresprogramm();

$fehlerArr = array();

if ($showPage->checkUG('aktiv')) {

	$myID = $showPage->userData->ID;

	$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

	if (!$showPage->checkUG('vorstand')) {
		unset($jpArr['typen']['vorstand']);
	}

	$sql = "
	SELECT
	  vereinID,
	  IF(datumVon='0000-00-00', '', DATE_FORMAT(datumVon, '%d.%m.%Y')) AS datumVon,
	  IF(datumBis='0000-00-00', '', DATE_FORMAT(datumBis, '%d.%m.%Y')) AS datumBis,
	  zeit, titel, ort, bemerkungen, gm300, gm50, gm25, gm10, mw300, mw50, mwlg, mwlp, mwba, js, vt, sa300, sa50, sa25, sa10, vs, wb, vorstand
	  , export
	  , zeitVon, zeitBis
	  
	FROM
	  jahresprogramm
	  
	WHERE
	  ID=? AND (registered_by=? OR {$showPage->userData->admin}=1)
	";
	$qry = $DB_LINK->query($sql, array($ID, $myID));
	if ($qry->rowCount() == 1) {
		$showPage->pageArr['platzhalter']['title'] = 'Anlass bearbeiten';
		$ac = 'mod';
		$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

	} else {
		$ID = 0;
		$ac = 'add';
		$showPage->pageArr['platzhalter']['title'] = 'Anlass hinzufügen';

	}

	$vArr = array();

	$vArr[0] = 'Ohne Zuteilung';
	$sql = "SELECT ID, name FROM vereine WHERE ID IN (SELECT vereinID FROM benutzervereine WHERE benutzerID=?) ORDER BY name";
	$qry = $DB_LINK->query($sql, array($myID));
	while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
		$vArr[$res['ID']] = $res['name'];
	}

	if (count($vArr) == 0) {
		$fehlerArr[] = 'Leider besitzen Sie keine Rechte zur Verwaltung von Anlässen.';
	}

	if (isset($_GET['send'])) {

		if (!isset($_POST['vereinID']) || !isset($vArr[$_POST['vereinID']])) {
			$fehlerArr[] = "Wählen Sie einen Verein aus";

		} else {
			$datenArr['vereinID'] = $_POST['vereinID'];

		}

		if (!isset($_POST['datumVon']) || $_POST['datumVon'] == '') {
			$fehlerArr[] = 'Sie haben kein "Datum von" eingegeben.';

		} elseif (!preg_match("/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/", $_POST['datumVon'])) {
			$fehlerArr[] = 'Sie haben ein ungültiges "Datum von" eingegeben.';

		} else {
			$datenArr['datumVon'] = $_POST['datumVon'];

		}

		if (!isset($_POST['datumBis']) || $_POST['datumBis'] == '') {
			$datenArr['datumBis'] = $datenArr['datumVon'];

		} elseif (!preg_match("/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/", $_POST['datumBis'])) {
			$fehlerArr[] = 'Sie haben ein ungültiges "Datum bis" eingegeben.';

		} else {
			$datenArr['datumBis'] = $_POST['datumBis'];

		}

		if (!isset($_POST['titel']) || $_POST['titel'] == '') {
			$fehlerArr[] = 'Sie haben keinen Titel eingegeben.';

		} else {
			$datenArr['titel'] = $_POST['titel'];

		}

		if (!isset($_POST['ort']) || $_POST['ort'] == '') {
			$fehlerArr[] = 'Sie haben keinen Ort eingegeben.';

		} else {
			$datenArr['ort'] = $_POST['ort'];

		}

		$datenArr['zeitVon'] = (isset($_POST['zeitVon'])) ? $_POST['zeitVon'] : '';
		$datenArr['zeitBis'] = (isset($_POST['zeitBis'])) ? $_POST['zeitBis'] : '';

		$datenArr['zeit'] = (isset($_POST['zeit'])) ? $_POST['zeit'] : '';

		if (isset($_POST['bemerkungen'])) {
			$datenArr['bemerkungen'] = $_POST['bemerkungen'];
		}

		foreach ($jpArr['typen'] AS $typ => $typData) {
			if (!isset($_POST['twahl']) || !in_array($typ, $_POST['twahl'])) {
				$datenArr[$typ] = 0;

			}
		}

		if (isset($_POST['twahl'])) {
			foreach ($_POST['twahl'] AS $typ) {
				if (array_key_exists($typ, $jpArr['typen'])) {
					$datenArr[$typ] = 1;

				}
			}
		} else {
			$fehlerArr[] = 'Sie müssen mindestens eine Kategorie auswählen!';

		}

		$datenArr['export'] = (isset($_POST['export']) && $_POST['export'] == 1) ? 1 : 0;

		if (count($fehlerArr) == 0) {

			$datenArr['ID'] = $ID;
			if ($datenArr['datumVon'] != '') {
				$xArr = explode(".", $datenArr['datumVon']);
				$datenArr['datumVon'] = "{$xArr[2]}-{$xArr[1]}-{$xArr[0]}";
			}

			if ($datenArr['datumBis'] != '') {
				$xArr = explode(".", $datenArr['datumBis']);
				$datenArr['datumBis'] = "{$xArr[2]}-{$xArr[1]}-{$xArr[0]}";
			}

			if ($ID == 0) {
				$datenArr['registered_by'] = $showPage->userData->ID;

				$ID = $bsvb->insertEntry('jahresprogramm', $datenArr);

				if ($showPage->checkUG('admin') == 1) {
					$DB_LINK->query("UPDATE jahresprogramm SET confirmed=NOW() WHERE ID=?", array($ID));

				} else {
					$to = "webmaster@bsv-buelach.ch";
					$toName = "webmaster@bsv-buelach.ch";
					$from = "webmaster@bsv-buelach.ch";
					$fromName = "webmaster@bsv-buelach.ch";
					$subject = "Neuer Anlass bei {$_SERVER['SERVER_NAME']}";

					$text = "Grüezi\n\nEs gibt einen neuen Anlass bei {$_SERVER['SERVER_NAME']}. Bitte prüfen und veröffentlichen oder löschen Sie diesen Anlass.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

					FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $text, "no", "txt");
//          FormMailer::sendMail("info@actra.ch", $toName, $from, $fromName, $subject, $text, "no", "txt");

				}
			} else {
				$bsvb->updateEntry('jahresprogramm', $ID, $datenArr);

				$to = "webmaster@bsv-buelach.ch";
				$toName = "webmaster@bsv-buelach.ch";
				$from = "webmaster@bsv-buelach.ch";
				$fromName = "webmaster@bsv-buelach.ch";
				$subject = "Anlass bei {$_SERVER['SERVER_NAME']} wurde geändert";

				$text = "Grüezi\n\nDer Anlass {$datenArr['titel']} wurde bei {$_SERVER['SERVER_NAME']} durch den Benutzer {$showPage->userData->vorname} {$showPage->userData->nachname} geändert.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

				FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $text, "no", "txt");
//        FormMailer::sendMail("info@actra.ch", $toName, $from, $fromName, $subject, $text, "no", "txt");

			}
			$DB_LINK->query("UPDATE jahresprogramm SET lastmod=NOW() WHERE ID=?", array($ID));

			$showPage->redirect("anlassDet-{$ID}.html?ac={$ac}");
		}
	}

	foreach ($vArr AS $key => $val) {
		$vereine .= "<option value=\"{$key}\"";
		if ($key == $datenArr['vereinID']) {
			$vereine .= ' selected="selected"';
		}
		$vereine .= ">{$val}</option>\n";
	}

	foreach ($jpArr['typen'] AS $typ => $typData) {
		$kategorien .= "<li><input type=\"checkbox\" name=\"twahl[]\" value=\"{$typ}\" id=\"twahl{$typ}\"";
		if (isset($datenArr[$typ]) && $datenArr[$typ] == 1) {
			$kategorien .= ' checked="checked"';
		}
		$kategorien .= "/> <label for=\"twahl{$typ}\">{$typData['titel']}</label></li>\n";
	}

}

if (count($fehlerArr) != 0) {
	$status = "<div id=\"formfehler\"><ul>\n";
	foreach ($fehlerArr as $key => $val) {
		$status .= "<li>{$val}</li>\n";
	}
	$status .= "</ul></div>";
}

$platzhalter['status'] = $status;
$platzhalter['ID'] = $ID;
$platzhalter['vereine'] = $vereine;
$platzhalter['kategorien'] = $kategorien;
$platzhalter['chkexport'] = ($datenArr['export'] == 1) ? ' checked="checked"' : '';

foreach ($datenArr AS $key => $val) {
	$platzhalter[$key] = $val;
}
/* EOF */