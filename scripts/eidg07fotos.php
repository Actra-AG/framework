<?php
$title = '';
$fotos = '';

$katID = 0;
if(isset($showPage -> arrVars[1])) {
  $katID = (int)$showPage -> arrVars[1];
}

$sql = "SELECT titel FROM alben WHERE ID='{p}'";
$qry = $DB_LINK -> query($sql, array($katID));
$res = $qry -> fetch_assoc();
$title = $res['titel'];

$fotos = '';
$sql = "SELECT * FROM fotos WHERE albumID='{p}' ORDER BY pos";
$qry = $DB_LINK -> query($sql, array($katID));
if($qry -> num_rows() == 0) {
	$fotos = '<p class="noentry">In dieser Kategorie gibt es noch keine Fotos.</p>';

} else {
	$fotos .= "<ul id=\"galerie\">\n";
  while($res = $qry -> fetch_assoc()) {
    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$res['ID'].'.jpg')) {
      $fotos .= "<li><a href=\"eidg07foto-{$katID}-{$res['ID']}.html\"><img src=\"/galerie/tnfoto{$res['ID']}.jpg\" width=\"125\" height=\"90\" alt=\"\" /></a></li>\n";

    }
  }
  $fotos .= "</ul>";
}

$platzhalter['title'] = $title;
$platzhalter['fotos'] = $fotos;
?>