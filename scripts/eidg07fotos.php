<?php
$title = '';
$fotos = '';

$katID = 0;
if (isset($showPage->arrVars[1])) {
	$katID = (int)$showPage->arrVars[1];
}

$sql = "SELECT titel FROM alben WHERE ID=?";
$qry = $DB_LINK->query($sql, array($katID));
$res = $qry->fetch(PDO::FETCH_ASSOC);
$title = $res['titel'];

$fotos = '';
$sql = "SELECT * FROM fotos WHERE albumID=? ORDER BY pos";
$qry = $DB_LINK->query($sql, array($katID));
if ($qry->rowCount() == 0) {
	$fotos = '<p class="noentry">In dieser Kategorie gibt es noch keine Fotos.</p>';

} else {
	$fotos .= "<ul id=\"galerie\">\n";
	while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.jpg')) {
			$href = "eidg07foto-{$katID}-{$res['ID']}.html";
			$src = "/galerie/tnfoto{$res['ID']}.jpg";
			$fotos .= "<li><a href=\"{$href}\"><img src=\"{$src}\" width=\"125\" height=\"90\" alt=\"\" /></a></li>\n";

		}
	}
	$fotos .= "</ul>";
}

$platzhalter['title'] = $title;
$platzhalter['fotos'] = $fotos;
/* EOF */