<?php
$bsvb = new bsvb();

$status = '';
$ID = '';

$datenArr['titel'] = '';

$fehlerArr = array();

if ($showPage->checkUG('redaktor')) {

	$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

	$sql = "SELECT titel FROM alben WHERE ID=?";
	$qry = $DB_LINK->query($sql, array($ID));
	if ($qry->rowCount() == 1) {
		$showPage->pageArr['platzhalter']['title'] = 'Album bearbeiten';
		$showPage->pageArr['grundkonf']['navigator']['title'] = 'Album bearbeiten';
		$ac = 'mod';
		$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

	} else {

		$ID = 0;
		$ac = 'add';
		$showPage->pageArr['platzhalter']['title'] = 'Album hinzufügen';
		$showPage->pageArr['grundkonf']['navigator']['title'] = 'Album hinzufügen';

	}

	if (isset($_GET['send'])) {

		if (!isset($_POST['titel']) || $_POST['titel'] == '') {
			$fehlerArr[] = 'Sie haben keinen Titel eingegeben.';

		} else {
			$datenArr['titel'] = $_POST['titel'];

		}

		if (count($fehlerArr) == 0) {

			if ($ID == 0) {
				$sql = "SELECT MAX(pos)+1 AS pos FROM alben";
				$qry = $DB_LINK->query($sql);
				$res = $qry->fetch(PDO::FETCH_ASSOC);
				$datenArr['pos'] = $res['pos'];
				$datenArr['typ'] = 1;

				$ID = $bsvb->insertEntry('alben', $datenArr);

			} else {
				$bsvb->updateEntry('alben', $ID, $datenArr);

			}

			$showPage->redirect("alben.html?{$ac}");
		}
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

foreach ($datenArr AS $key => $val) {
	$platzhalter[$key] = htmlentities($val);
}
/* EOF */