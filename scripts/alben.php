<?php
$alben = "";

$sql = "SELECT ID, titel FROM alben WHERE typ=1 ORDER BY pos";
$qry = $DB_LINK->query($sql);
if ($qry->rowCount() == 0) {
	$alben = "<p>Es gibt zurzeit keine Alben.</p>";

} else {
	$alben = "<ul class=\"normliste\">\n";
	while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
		$href = "fotos-{$res['ID']}.html";
		$alben .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
	}
	$alben .= "</ul>\n";

}

$platzhalter['alben'] = $alben;
/* EOF */