<?php
$toID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

$showPage->pageArr['platzhalter']['title'] = 'BSVB kontaktieren';

$sql = "
SELECT
  vorname, nachname, email
  
FROM
  benutzer
  
WHERE
  ID=?
";
$qry = $DB_LINK->query($sql, array($toID));
if ($qry->rowCount() == 1) {
	$res = $qry->fetch(PDO::FETCH_ASSOC);
	$showPage->pageArr['platzhalter']['title'] = "{$res['vorname']} {$res['nachname']} kontaktieren";

}
/* EOF */