<?php
$titel = '';
$news = '';

$ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

$sql = "SELECT titel, teaser, text FROM news WHERE ID='{$ID}'";
$qry = $DB_LINK->query($sql);
if ($qry->rowCount() != 1) {
	$showPage->redirect("newsArchiv.html");
}
$res = $qry->fetch(PDO::FETCH_ASSOC);

$news = "<div class=\"startnews group\"><h3>{$res['titel']}</h3>{$res['teaser']}{$res['text']}</div>\n";

$titel = $res['titel'];
$showPage->pageArr['platzhalter']['title'] = $titel;
$showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

$platzhalter['titel'] = $titel;
$platzhalter['news'] = $news;
/* EOF */