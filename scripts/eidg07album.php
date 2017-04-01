<?php
$eidgalbum = '';

$sql = "SELECT * FROM alben WHERE typ=2 ORDER BY titel";
$qry = $DB_LINK->query($sql);
while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
	$href = "eidg07fotos-{$res['ID']}.html";
	$eidgalbum .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
}

$platzhalter['eidgalbum'] = $eidgalbum;
/* EOF */