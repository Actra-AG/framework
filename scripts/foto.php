<?php
$titel = '';
$albumID = '';
$navi = '';
$foto = '';
$text = '';

$albumID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;
$fotoID = (isset($showPage->arrVars[2])) ? $showPage->arrVars[2] : 0;

$sql = "SELECT titel FROM alben WHERE ID=?";
$qry = $DB_LINK->query($sql, array($albumID));
if ($qry->rowCount() != 1) {
	$showPage->redirect("alben.html");
}
$res = $qry->fetch(PDO::FETCH_ASSOC);

$titel = $res['titel'];
$showPage->pageArr['platzhalter']['title'] = $titel;
$showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

$sql = "SELECT f.text, f.typ, (SELECT ID FROM fotos WHERE albumID=f.albumID AND pos<f.pos ORDER BY pos DESC LIMIT 1) AS lastID, (SELECT ID FROM fotos WHERE albumID=f.albumID AND pos>f.pos ORDER BY pos LIMIT 1) AS nextID FROM fotos f WHERE f.ID=?";
$qry = $DB_LINK->query($sql, array($fotoID));
if ($qry->rowCount() != 1) {
	$showPage->redirect("fotos-{$albumID}.html");
}
$res = $qry->fetch(PDO::FETCH_ASSOC);

if ($res['lastID'] != '') {
	$href = "foto-{$albumID}-{$res['lastID']}.html";
	$navi .= "<li><a href=\"{$href}\">&laquo; zur&uuml;ck</a></li>\n";
}
if ($res['nextID'] != '') {
	$href = "foto-{$albumID}-{$res['nextID']}.html";
	$navi .= "<li><a href=\"{$href}\">vor &raquo;</a></li>\n";
}


if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.' . $res['typ'])) {
	$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.' . $res['typ']);
	$src = "/galerie/foto{$fotoID}.{$res['typ']}";
	$foto = "<div id=\"img\"><img src=\"{$src}\" {$imgArr[3]} alt=\"\" /></div>";
}

if ($res['text'] != '') {
	$text = "<p class=\"legende\">" . nl2br($res['text']) . "</p>";
}

$platzhalter['titel'] = $titel;
$platzhalter['albumID'] = $albumID;
$platzhalter['navi'] = $navi;
$platzhalter['foto'] = $foto;
$platzhalter['text'] = $text;
/* EOF */