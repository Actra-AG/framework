<?php
$status = '';
$x = '';
$liste = '';

$fehlerArr = array();

if ($showPage->checkUG('redaktor') && isset($showPage->arrVars[2])) {
	$benutzerID = (int)$showPage->userData->ID;

	$oArr['frontend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/pages/';
	$oArr['frontend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/config/';
	$oArr['frontend']['name'] = "Frontend";

	$oArr['backend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/pages/';
	$oArr['backend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/config/';
	$oArr['backend']['name'] = "Backend";

	$ort = 'frontend';
	if (isset($showPage->arrVars[1]) && isset($oArr[$showPage->arrVars[1]])) {
		$ort = $showPage->arrVars[1];
	}

	$path_pages = $oArr[$ort]['pages'];
	$path_config = $oArr[$ort]['config'];
	$ortname = $oArr[$ort]['name'];

	$seite = $showPage->arrVars[2];

	if (!file_exists($path_pages . "{$seite}.html") || !file_exists($path_config . "{$seite}.php")) {
		$showPage->redirect("seiten.html");
	}

	$x = "{$seite} ({$ort})";

	$paramsArr[] = $ort;
	$paramsArr[] = $seite;

	$sql = "SELECT s.ID, DATE_FORMAT(s.datum, '%d.%m.%Y %T') AS datum, CONCAT(b.vorname, ' ', b.nachname) AS name FROM seiteninhalte s LEFT JOIN benutzer b ON s.benutzerID=b.ID WHERE s.ort=? AND s.seite=? ORDER BY s.datum DESC";
	$qry = $DB_LINK->query($sql, $paramsArr);
	if ($qry->rowCount() == 0) {
		$liste = "<p>Von dieser Seite gibt es keine archivierte Versionen.</p>";

	} else {
		$liste = "<ul>\n";
		while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
			$href = "archiv-seite-{$res['ID']}.html";
			$liste .= "<li><a href=\"{$href}\">{$res['datum']}</a> [{$res['name']}]</li>";
		}

		$liste .= "</ul>";

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
$platzhalter['x'] = $x;
$platzhalter['liste'] = $liste;
/* EOF */