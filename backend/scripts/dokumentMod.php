<?php
$bsvb = new bsvb();

$status = '';
$objekt = '';
$objektID = '';
$ID = '';
$dokument = '';

$datenArr['titel'] = '';

$fehlerArr = array();

if ($showPage->checkUG('aktiv')) {

	$myID = $showPage->userData->ID;
	$objekt = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 'anlass';
	$objektID = (isset($showPage->arrVars[2])) ? $showPage->arrVars[2] : 0;
	$ID = (isset($showPage->arrVars[3])) ? $showPage->arrVars[3] : 0;

	if ($objekt == 'anlass') {
		$sql = "
	  SELECT
  	  titel
	  
  	FROM
	    jahresprogramm
	  
	  WHERE
  	  ID=? AND (registered_by=? OR {$showPage->userData->admin}=1)
  	";
		$qry = $DB_LINK->query($sql, array($objektID, $myID));
		if ($qry->rowCount() == 0) {
			$showPage->redirect("jp.html");
		}
		$next = "anlassDet-{$objektID}";

	} else {
		$showPage->redirect("start.html");

	}

	$sql = "SELECT titel FROM dokumente WHERE objekt=? AND objektID=? AND ID=?";
	$qry = $DB_LINK->query($sql, array($objekt, $objektID, $ID));
	if ($qry->rowCount() == 1) {
		$showPage->pageArr['platzhalter']['title'] = 'Dokument bearbeiten';
		$ac = 'mod';
		$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

	} else {
		$ID = 0;
		$ac = 'add';
		$showPage->pageArr['platzhalter']['title'] = 'Dokument hinzufügen';

	}

	if (isset($_GET['send'])) {

		$extension = '';
		if (isset($_FILES['doc']) && $_FILES['doc']['name'] != '') {
			$mimetype = $_FILES['doc']['type'];
			$sql = "SELECT extension FROM dateiformate WHERE mimetype=? AND FIND_IN_SET('dokumente', arten)!=0";
			$qry = $DB_LINK->query($sql, array($mimetype));
			if ($qry->rowCount() == 1) {
				$res = $qry->fetch(PDO::FETCH_ASSOC);
				$extension = $res['extension'];
				$datenArr['type'] = $mimetype;

				if (preg_match("/^([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9]{2,3})$/", $_FILES['doc']['name'])) {
					$datenArr['dateiname'] = $_FILES['doc']['name'];

				} else {
					$fehlerArr[] = 'Die Datei hat einen ungültigen Dateinamen. Dieser darf keine Sonder- und Leerzeichen beinhalten!';
				}

			} else {
				$fehlerArr[] = 'Die Datei hat ein ungültiges Dateiformat: ' . $mimetype;

			}

		} elseif ($ac == 'add') {
			$fehlerArr[] = 'Sie haben keine Datei ausgewählt.';

		}

		if (isset($_POST['titel'])) {
			$datenArr['titel'] = $_POST['titel'];
		}

		if (count($fehlerArr) == 0) {

			if ($ID == 0) {
				$datenArr['objekt'] = $objekt;
				$datenArr['objektID'] = $objektID;

				$ID = $bsvb->insertEntry('dokumente', $datenArr);

				move_uploaded_file($_FILES['doc']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $ID . '.' . $extension);
				chmod($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $ID . '.' . $extension, 0777);

			} else {
				$bsvb->updateEntry('dokumente', $ID, $datenArr);

			}

			$showPage->redirect("{$next}.html?{$ac}");
		}
	}

	if ($ac == 'add') {
		$doctitel = 'Dokument';
		$doczusatz = '';
		$dokument .= "<dl><dt><label for=\"doc\">{$doctitel}</label></dt><dd><input type=\"file\" name=\"doc\" id=\"doc\" /></dd>\n";
		if ($ID != 0) {
			$dokument .= "<!--<input type=\"submit\" class=\"submit\" name=\"docAdd\" value=\"hochladen\" />-->";
		}
		$dokument .= "</dl>";
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
$platzhalter['objekt'] = $objekt;
$platzhalter['objektID'] = $objektID;
$platzhalter['ID'] = $ID;
$platzhalter['dokument'] = $dokument;

foreach ($datenArr AS $key => $val) {
	$platzhalter[$key] = $val;
}
/* EOF */