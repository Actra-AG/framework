<?php
$ehren = '';

$sql = "SELECT * FROM benutzer WHERE ehren=1 ORDER BY ernannt, nachname, vorname";
$qry = $DB_LINK->query($sql);
while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
	$ehren .= "<tr><td>{$res['nachname']}</td><td>{$res['vorname']}</td><td>{$res['plz']} {$res['ort']}</td><td>{$res['ernannt']}</td></tr>\n";
}

$platzhalter['ehren'] = $ehren;
/* EOF */