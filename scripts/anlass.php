<?php
$bsvb = new bsvb();

$backlink = '';
$verein = '';
$datum = '';
$zeit = '';
$ort = '';
$bemerkungen = '';
$dokumente = '';
$export = '';

$jpArr = $bsvb -> getJahresprogramm();

$gruppe = (isset($showPage -> arrVars[1]) && array_key_exists($showPage -> arrVars[1], $jpArr['gruppen'])) ? $showPage -> arrVars[1] : 'sa';

if(count($jpArr['gruppen'][$gruppe]) == 0) {
	$typ = $gruppe;
	
} else {
  $typ = (isset($showPage -> arrVars[2]) && in_array($showPage -> arrVars[2], $jpArr['gruppen'][$gruppe])) ? $showPage -> arrVars[2] : current($jpArr['gruppen'][$gruppe]);  
  
  $gruppen = "<ul>\n";
  foreach($jpArr['gruppen'][$gruppe] AS $val) {
  	$gt = ($val == $typ) ? "<strong>{$jpArr['typen'][$val]['titel']}</strong>" : "<a href=\"jp-{$gruppe}-{$val}.html\">{$jpArr['typen'][$val]['titel']}</a>";
  	$gruppen .= "<li>{$gt}</li>\n";
  }
  $gruppen .= "</ul>\n";

}

$showPage -> pageArr['navistufe'][2] = "jp{$gruppe}";

$jahr = (isset($showPage -> arrVars[3])) ? $showPage -> arrVars[3] : date("Y");
$ID = (isset($showPage -> arrVars[4])) ? $showPage -> arrVars[4] : 0;

$backlink = "jp-{$gruppe}-{$typ}-{$jahr}.html";

$sql = "
SELECT
  v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.zeit, p.bemerkungen, p.export
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.ID='{p}'
";
$qry = $DB_LINK -> query($sql, array($ID));
if($qry -> num_rows() == 0) { $showPage -> redirect("jp.html"); }
$res = $qry -> fetch_object();

$showPage -> pageArr['platzhalter']['title'] = $res -> titel;
$verein = ($res -> verein == '') ? 'unbekannt' : $res -> verein;
$datum = ($res -> datumVon == $res -> datumBis) ? $res -> datumVon : "{$res -> datumVon} - {$res -> datumBis}";
$zeit = $res -> zeit;
$ort = $res -> ort;
$bemerkungen = ($res -> bemerkungen == '') ? '' : "<dl class=\"group\"><dt>Bemerkungen:</dt><dd>".nl2br($res -> bemerkungen)."</dd></dl>";
$export = ($res->export == 0) ? '' : "<dl class=\"group\"><dt>Kalenderexport:</dt><dd><a href=\"/calendar/{$ID}/event.ics\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a></dd></dl>";

$pdfArr = array();

$sql = "
SELECT
  d.ID, d.dateiname, d.titel, f.extension
        
FROM
  dokumente d
  INNER JOIN dateiformate f ON d.type=f.mimetype
        
WHERE
  d.objekt='anlass' AND d.objektID='{p}'
      
ORDER BY
  titel";
$qry = $DB_LINK -> query($sql, array($ID));
while($res = $qry -> fetch_assoc($qry)) {
  if(file_exists($_SERVER['DOCUMENT_ROOT'].'/dokumente/'.$res['ID'].'.'.$res['extension'])) {
    $size = $showPage -> bytestostring(filesize($_SERVER['DOCUMENT_ROOT'].'/dokumente/'.$res['ID'].'.'.$res['extension']));
    $key = md5("aasmdsjtk{$res['ID']}asujdt3?nz34g");

    $doktitel = ($res['titel'] == '') ? 'ohne Titel' : $res['titel'];
    $pdfArr[] = "<li><a href=\"/dokumente/{$res['ID']}/{$key}/".urlencode($res['dateiname'])."\">{$doktitel}</a></li>\n";
  }
}
      
if(count($pdfArr) != 0) {
  $dokumente = "<dl class=\"group\"><dt>Dokument(e):</dt><dd><ul class=\"pdflink\">\n".implode("\n", $pdfArr)."</ul></dd></dl>";
}


$platzhalter['backlink'] = $backlink;
$platzhalter['verein'] = $verein;
$platzhalter['datum'] = $datum;
$platzhalter['zeit'] = $zeit;
$platzhalter['ort'] = $ort;
$platzhalter['bemerkungen'] = $bemerkungen;
$platzhalter['dokumente'] = $dokumente;
$platzhalter['export'] = $export;
?>