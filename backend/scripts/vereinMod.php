<?php
$bsvb = new bsvb();

$status = '';
$ID = 0;

$datenArr['name'] = '';

$fehlerArr = array();

if ($showPage->checkUG('admin')) {

	$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

	$sql = "
	SELECT
	  name
	  
	FROM
	  vereine
	  
	WHERE
	  ID=?
	";
	$qry = $DB_LINK->query($sql, array($ID));
	if ($qry->rowCount() == 1) {
		$showPage->pageArr['platzhalter']['title'] = 'Verein bearbeiten';
		$ac = 'mod';
		$datenArr = $qry->fetch(PDO::FETCH_ASSOC);

	} else {
		$ID = 0;
		$ac = 'add';
		$showPage->pageArr['platzhalter']['title'] = 'Verein hinzufügen';

	}

	$optArr[0] = 'Nein';
	$optArr[1] = 'Ja';

	if (isset($_GET['send'])) {

		if (!isset($_POST['name']) || $_POST['name'] == '') {
			$fehlerArr[] = 'Sie haben keinen Vereinsnamen eingegeben.';

		} else {
			$datenArr['name'] = $_POST['name'];

		}

		if (count($fehlerArr) == 0) {

			$datenArr['ID'] = $ID;

			if ($ID == 0) {
				$datenArr['registered_by'] = $showPage->userData->ID;
				$ID = $bsvb->insertEntry('vereine', $datenArr);

			} else {
				$bsvb->updateEntry('vereine', $ID, $datenArr);

			}

			$showPage->redirect("vereine.html?ac={$ac}");
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
	$platzhalter[$key] = $val;
}
/* EOF */