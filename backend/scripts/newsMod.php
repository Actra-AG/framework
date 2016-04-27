<?php
$bsvb = new bsvb();

$status = '';
$ID = 0;
$archiv0 = '';
$archiv1 = '';

$datenArr['datum'] = date("d.m.Y");
$datenArr['titel'] = '';
$datenArr['teaser'] = '';
$datenArr['text'] = '';
$datenArr['archiv'] = 0;

$fehlerArr = array();

if ($showPage->checkUG('redaktor')) {

	$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

	$sql = "
	SELECT
	  archiv, titel, teaser, text, IF(datum='0000-00-00', '', DATE_FORMAT(datum, '%d.%m.%Y')) AS datum
	  
	FROM
	  news
	  
	WHERE
	  ID=?
	";
	$qry = $DB_LINK->query($sql, array($ID));
	if ($qry->rowCount() == 1) {
		$showPage->pageArr['platzhalter']['title'] = 'Neuigkeit bearbeiten';
		$ac = 'mod';
		$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

	} else {
		$ID = 0;
		$ac = 'add';
		$showPage->pageArr['platzhalter']['title'] = 'Neuigkeit hinzufügen';

	}

	if (isset($_GET['send'])) {

		if (!isset($_POST['datum']) || $_POST['datum'] == '') {
			$fehlerArr[] = 'Sie haben kein Datum eingegeben.';

		} elseif (!preg_match("/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/", $_POST['datum'])) {
			$fehlerArr[] = 'Sie haben ein ungültiges Datum eingegeben.';

		} else {
			$datenArr['datum'] = $_POST['datum'];

		}

		if (!isset($_POST['titel']) || $_POST['titel'] == '') {
			$fehlerArr[] = 'Sie haben keinen Titel eingegeben.';

		} else {
			$datenArr['titel'] = $_POST['titel'];

		}

		if (isset($_POST['teaser'])) {
			$datenArr['teaser'] = $_POST['teaser'];
		}
		if (isset($_POST['text'])) {
			$datenArr['text'] = $_POST['text'];
		}

		if (!isset($_POST['archiv']) || ($_POST['archiv'] != 0 && $_POST['archiv'] != 1)) {
			$fehlerArr[] = 'Sie haben nichts bei Archiv ausgewählt.';

		} else {
			$datenArr['archiv'] = $_POST['archiv'];

		}

		if (count($fehlerArr) == 0) {

			$datenArr['ID'] = $ID;
			if ($datenArr['datum'] != '') {
				$xArr = explode(".", $datenArr['datum']);
				$datenArr['datum'] = "{$xArr[2]}-{$xArr[1]}-{$xArr[0]}";
			}

			if ($ID == 0) {
				$datenArr['registered_by'] = $showPage->userData->ID;
				$datenArr['typ'] = 1;
				$ID = $bsvb->insertEntry('news', $datenArr);

			} else {
				$bsvb->updateEntry('news', $ID, $datenArr);

			}

			$showPage->redirect("news.html?ac={$ac}");
		}
	}

	if ($datenArr['archiv'] == 0) {
		$archiv0 = ' checked="checked"';
	}
	if ($datenArr['archiv'] == 1) {
		$archiv1 = ' checked="checked"';
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
$platzhalter['archiv0'] = $archiv0;
$platzhalter['archiv1'] = $archiv1;

foreach ($datenArr AS $key => $val) {
	$platzhalter[$key] = $val;
}
/* EOF */