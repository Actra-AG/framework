<?php
$bsvb = new bsvb();

$jahresnavi = '';
$liste = '';

$jpArr = $bsvb -> getJahresprogramm();

$fehlerArr = array();

$fn = 'adm_vorstand';

if($showPage -> checkUG('vorstand')) {
	
	$typ = 'vorstand';
  $addCond = (count($jpArr['typen'][$typ]['conditions']) == 0) ? "" : " AND ".implode(" AND ", $jpArr['typen'][$typ]['conditions']);

  $sql = "SELECT DATE_FORMAT(MAX(lastmod), '%d.%m.%Y %T') AS lastmod FROM jahresprogramm WHERE {p}=1";
  $qry = $DB_LINK -> query($sql, array($typ));
  $res = $qry -> fetch_assoc();
  $lastmod = ($res['lastmod'] != '') ? $res['lastmod'] : 'unbekannt';

  $sql = "SELECT MIN(YEAR(p.datumVon)) AS minJahr, MAX(YEAR(p.datumBis)) AS maxJahr FROM jahresprogramm p WHERE p.{p}=1 AND p.confirmed!='0000-00-00 00:00:00'{$addCond}"; // , MAX(p.lastmod) AS lastmod
  $qry = $DB_LINK -> query($sql, array($typ));
  $res = $qry -> fetch_assoc();
  $minJahr = ($res['minJahr'] != '') ? $res['minJahr'] : date("Y");
  $maxJahr = ($res['maxJahr'] != '') ? $res['maxJahr'] : date("Y");
  $currJahr = (isset($showPage -> arrVars[1]) && is_numeric($showPage -> arrVars[1]) && $showPage -> arrVars[1] >= $minJahr && $showPage -> arrVars[1] <= $maxJahr) ? $showPage -> arrVars[1] : date("Y");
  if($currJahr < $minJahr || $currJahr > $maxJahr) { $currJahr = $minJahr; }

  $jArr = array();
  for($i = $maxJahr; $i >= $minJahr; $i--) {
	  if($i == $currJahr) {
  		$jArr[] = "<strong>{$i}</strong>";

  	} else {
   		$jArr[] = "<a href=\"vorstand-{$i}.html\">{$i}</a>";

   	}
  }
  $jahresnavi = "<p>".implode(" | ", $jArr)."</p>";

  $cond = "WHERE {p}=1 AND confirmed!='0000-00-00 00:00:00' AND YEAR(p.datumVon)<='{p}' AND YEAR(p.datumBis)>='{p}'{$addCond}";
  $paramsArr[] = $typ;
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

  $fn = "jahresprogramm{$typ}";

  $ox = "";
  $orderby = "p.datumVon, p.datumBis, p.zeit";
  if(isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) { $_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']); }
  if(isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) { $_SESSION[$fn]['ox'] = $_GET['ox']; }
  if(isset($_SESSION[$fn]['orderby'])) { $orderby = $_SESSION[$fn]['orderby']; }
  if(isset($_SESSION[$fn]['ox'])) { $ox = $_SESSION[$fn]['ox']; }

  $sql = "
  SELECT
    COUNT(p.ID) AS anz
  
  FROM
    jahresprogramm p
  
  {$cond}
  ";
  $qry = $DB_LINK -> query($sql, $paramsArr);
  $res = $qry -> fetch_object();
  if($res -> anz == 0) {
    $liste = "<p class=\"no-entry\">Es sind keine Anlässe erfasst.</p>";

  } else {
    $liste .= "<table cellspacing=\"0\" summary=\"\" class=\"mitglieder\">\n<thead>\n".$showPage -> dynTableHeader($fArr, $orderby, $ox)."</thead>\n<tbody>\n";
  
    $i = 0;
    $sql = "
    SELECT
      ID, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort
    
    FROM
      jahresprogramm p
      
    {$cond}
    
    ORDER BY
      {p} {p}
    ";
    $paramsArr[] = $orderby;
    $paramsArr[] = $ox;

    $qry = $DB_LINK -> query($sql, $paramsArr);
    while($res = $qry -> fetch_object()) {
  	  $i++;
  	  $alt = ($i%2 == 0) ? ' class="alt"' : '';
  	  $datum = ($res -> datumVon == $res -> datumBis) ? $res -> datumVon : "{$res -> datumVon} - <br />{$res -> datumBis}";

      $liste .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"anlassDet-{$res -> ID}.html\">{$res -> titel}</a></td>\n<td>{$res -> ort}</td>\n</tr>\n";
    }
    $liste .= "</tbody>\n</table>";
  }
}

$platzhalter['jahresnavi'] = $jahresnavi;
$platzhalter['liste'] = $liste;
?>