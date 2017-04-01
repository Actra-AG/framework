<?php
$titel = "";
$fotos = "";

$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

$sql = "SELECT titel FROM alben WHERE ID=?";
$qry = $DB_LINK->query($sql, array($ID));
if ($qry->rowCount() != 1) {
	$showPage->redirect("alben.html");
}
$res = $qry->fetch(PDO::FETCH_ASSOC);

$titel = $res['titel'];
$showPage->pageArr['platzhalter']['title'] = $titel;
$showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

$fn = "fotos{$ID}";

$cond = "albumID=?";
$paramsArr[] = $ID;

$pos = 0;
$proSeite = 24;

if (isset($_GET['filter']) && $_GET['filter'] == 'reset' && isset($_SESSION[$fn])) {
	unset($_SESSION[$fn]);
}
if (isset($_GET['pos'])) {
	$_SESSION[$fn]['pos'] = $_GET['pos'];
}
if (isset($_SESSION[$fn]['pos'])) {
	$pos = (int)$_SESSION[$fn]['pos'];
}

if ($pos < 0) {
	$pos = 0;
}

$sql = "
SELECT
  COUNT(ID) AS anz

FROM
  fotos

WHERE
  {$cond}
";
$qry = $DB_LINK->query($sql, $paramsArr);
$res = $qry->fetchObject();
if ($res->anz == 0) {
	$fotos = "<p>Es gibt keine Fotos in diesem Album.</p>";

} else {

	$pagination = $showPage->getPagenavi("fotos-{$ID}", $res->anz, $pos, $proSeite);

	$sql = "
  SELECT
    ID, typ

  FROM
    fotos

  WHERE
    {$cond}

  ORDER BY
    pos

  LIMIT
    {$pos}, {$proSeite}
  ";

	$qry = $DB_LINK->query($sql, $paramsArr);
	$fotos .= $pagination;
	$fotos .= "<div id=\"thumbnails\">\n";
	while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

		if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.' . $res['typ'])) {
			$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.' . $res['typ']);
			$href = "foto-{$ID}-{$res['ID']}.html";
			$src = "/galerie/tnfoto{$res['ID']}.{$res['typ']}";
			$fotos .= "<a href=\"{$href}\"><img src=\"{$src}\" {$imgArr[3]} alt=\"\" /></a>\n";
		}
	}
	$fotos .= "</div>\n";
//  $fotos .= $pagination;
}

$platzhalter['titel'] = $titel;
$platzhalter['fotos'] = $fotos;
/* EOF */