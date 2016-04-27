<?php
$title = '';
$katID = '';
$fotoID = '';
$imgsize = '';
$text = '';

$katID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;
$fotoID = (isset($showPage->arrVars[2])) ? $showPage->arrVars[2] : 0;

if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.jpg')) {
	$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.jpg');
	$imgsize = $imgArr[3];

}

$sql = "SELECT titel FROM alben WHERE ID=?";
$qry = $DB_LINK->query($sql, array($katID));
$res = $qry->fetch(PDO::FETCH_ASSOC);
$title = $res['titel'];

$sql = "SELECT text FROM fotos WHERE ID =?";
$qry = $DB_LINK->query($sql, array($fotoID));
$res = $qry->fetch(PDO::FETCH_ASSOC);
if ($res['text'] != '') {
	$text = '<p>' . nl2br($res['text']) . '</p>';
}

$platzhalter['title'] = $title;
$platzhalter['katID'] = $katID;
$platzhalter['fotoID'] = $fotoID;
$platzhalter['imgsize'] = $imgsize;
$platzhalter['text'] = $text;
/* EOF */