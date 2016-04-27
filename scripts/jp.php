<?php
$bsvb = new bsvb();

$lastmod = 'unbekannt';
$intro = '';
$gruppen = '';
$jahresnavi = '';
$liste = '';

$jpArr = $bsvb->getJahresprogramm();

$gruppe = (isset($showPage->arrVars[1]) && array_key_exists($showPage->arrVars[1], $jpArr['gruppen']) && $showPage->arrVars[1] != 'vorstand') ? $showPage->arrVars[1] : 'sa';

if (count($jpArr['gruppen'][$gruppe]) == 0) {
	$typ = $gruppe;

} else {
	$typ = (isset($showPage->arrVars[2]) && in_array($showPage->arrVars[2], $jpArr['gruppen'][$gruppe])) ? $showPage->arrVars[2] : current($jpArr['gruppen'][$gruppe]);

	$gruppen = "<div id=\"nav-content\" class=\"group\"><ul>\n";
	foreach ($jpArr['gruppen'][$gruppe] AS $val) {
		$href = "jp-{$gruppe}-{$val}.html";
		$gt = ($val == $typ) ? "<strong>{$jpArr['typen'][$val]['kurz']}</strong>" : "<a href=\"{$href}\">{$jpArr['typen'][$val]['kurz']}</a>";
		$gruppen .= "<li>{$gt}</li>\n";
	}
	$gruppen .= "</ul>\n</div>";

}
$showPage->pageArr['platzhalter']['title'] = $jpArr['typen'][$typ]['titel'];
$showPage->pageArr['navistufe'][2] = "jp{$gruppe}";

$intro = (isset($jpArr['typen'][$typ]['intro'])) ? $jpArr['typen'][$typ]['intro'] : '';
$addCond = (count($jpArr['typen'][$typ]['conditions']) == 0) ? "" : " AND " . implode(" AND ", $jpArr['typen'][$typ]['conditions']);

$sql = "SELECT DATE_FORMAT(MAX(lastmod), '%d.%m.%Y %T') AS lastmod FROM jahresprogramm WHERE {$typ}=1";
$qry = $DB_LINK->query($sql, array());
$res = $qry->fetch(PDO::FETCH_ASSOC);
$lastmod = ($res['lastmod'] != '') ? $res['lastmod'] : 'unbekannt';

$field = 'p.'.$typ;
$sql = "SELECT MIN(YEAR(p.datumVon)) AS minJahr, MAX(YEAR(p.datumBis)) AS maxJahr FROM jahresprogramm p WHERE {$field}=1 AND p.confirmed!='0000-00-00 00:00:00'{$addCond}";
$qry = $DB_LINK->query($sql, array($typ));
$res = $qry->fetch(PDO::FETCH_ASSOC);
$minJahr = ($res['minJahr'] != '') ? $res['minJahr'] : date("Y");
$maxJahr = ($res['maxJahr'] != '') ? $res['maxJahr'] : date("Y");
$currJahr = (isset($showPage->arrVars[3]) && is_numeric($showPage->arrVars[3]) && $showPage->arrVars[3] >= $minJahr && $showPage->arrVars[3] <= $maxJahr) ? $showPage->arrVars[3] : date("Y");
if ($currJahr < $minJahr || $currJahr > $maxJahr) {
	$currJahr = $minJahr;
}

$jArr = array();
for ($i = $maxJahr; $i >= $minJahr; $i--) {
	if ($i == $currJahr) {
		$jArr[] = "<strong>{$i}</strong>";

	} else {
		$href = "jp-{$gruppe}-{$typ}-{$i}.html";
		$jArr[] = "<a href=\"{$href}\">{$i}</a>";

	}
}
$jahresnavi = "<ul id=\"nav-year\" class=\"group\"><li>" . implode("</li><li>", $jArr) . "</li></ul>";

$cond = "WHERE {$typ}=1 AND confirmed!='0000-00-00 00:00:00' AND YEAR(p.datumVon)<=? AND YEAR(p.datumBis)>=?{$addCond}";
$paramsArr[] = $currJahr;
$paramsArr[] = $currJahr;

$fArr['p.datum']['attributes'] = '';
$fArr['p.datum']['order'] = 0;
$fArr['p.datum']['ox'] = '';
$fArr['p.datum']['value'] = 'Datum';

$fArr['p.titel']['attributes'] = '';
$fArr['p.titel']['order'] = 0;
$fArr['p.titel']['ox'] = '';
$fArr['p.titel']['value'] = 'Titel';

$fArr['p.ort']['attributes'] = '';
$fArr['p.ort']['order'] = 0;
$fArr['p.ort']['ox'] = '';
$fArr['p.ort']['value'] = 'Ort';

$fArr['p.export']['attributes'] = '';
$fArr['p.export']['order'] = 0;
$fArr['p.export']['ox'] = '';
$fArr['p.export']['value'] = '&nbsp;';

$fn = "jahresprogramm{$typ}";

$ox = "";
$orderby = "p.datumVon, p.datumBis, p.zeit";
if (isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) {
	$_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']);
}
if (isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) {
	$_SESSION[$fn]['ox'] = $_GET['ox'];
}
if (isset($_SESSION[$fn]['orderby'])) {
	$orderby = $_SESSION[$fn]['orderby'];
}
if (isset($_SESSION[$fn]['ox'])) {
	$ox = $_SESSION[$fn]['ox'];
}

$sql = "
SELECT
  COUNT(p.ID) AS anz
  
FROM
  jahresprogramm p
  
{$cond}
";
$qry = $DB_LINK->query($sql, $paramsArr);
$res = $qry->fetchObject();
if ($res->anz == 0) {
	$liste = "<p class=\"no-entry\">Es sind keine Anlässe erfasst.</p>";

} else {
	$liste .= "<table cellspacing=\"0\" class=\"normtable\">\n<thead>\n" . $showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

	$i = 0;
	$sql = "
  SELECT
    ID, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.export
    
      
  FROM
    jahresprogramm p
      
  {$cond}
    
  ORDER BY
    {$orderby} {$ox}

  ";

	$qry = $DB_LINK->query($sql, $paramsArr);
	while ($res = $qry->fetchObject()) {
		$i++;
		$alt = ($i % 2 == 0) ? ' class="alt"' : '';
		$datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res->datumVon} - <br />{$res->datumBis}";
		$filename = 'event.ics';
		$href = "/calendar/{$res->ID}/{$filename}";
		$export = ($res->export == 1) ? "<a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a>" : '';

		$href = "anlass-{$gruppe}-{$typ}-{$currJahr}-{$res->ID}.html";
		$liste .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"{$href}\">{$res->titel}</a></td>\n<td>{$res->ort}</td>\n<td>{$export}</td></tr>\n";
	}
	$liste .= "</tbody>\n</table>";

}

$platzhalter['lastmod'] = $lastmod;
$platzhalter['intro'] = $intro;
$platzhalter['gruppen'] = $gruppen;
$platzhalter['jahresnavi'] = $jahresnavi;
$platzhalter['liste'] = $liste;
/* EOF */