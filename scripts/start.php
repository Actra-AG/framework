<?php
$news = '';

$sql = "SELECT * FROM news WHERE archiv=0 AND typ=1 ORDER BY datum DESC";
$qry = $DB_LINK -> query($sql);
if($qry -> num_rows() == 0) {
  $news = "<p>Zurzeit gibt es keine Neuigkeiten.</p>";
	
} else {
  while($res = $qry -> fetch_assoc()) {

    $news .= "<div class=\"startnews group\"><h3>{$res['titel']}</h3>{$res['teaser']}<p>\n";
    if($res['text'] != "") { $news .= "<a href=\"newsDetails-{$res['ID']}.html\">weitere Informationen</a></p>\n"; }
    $news .= "</div>\n";

  }

}

$platzhalter['news'] = $news;
?>