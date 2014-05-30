<?php
$eidgalbum = '';

$sql = "SELECT * FROM alben WHERE typ=2 ORDER BY titel";
$qry =$DB_LINK -> query($sql);
while($res = $qry -> fetch_assoc()) {
  $eidgalbum .= "<li><a href=\"eidg07fotos-{$res['ID']}.html\">{$res['titel']}</a></li>\n";
}

$platzhalter['eidgalbum'] = $eidgalbum;
?>