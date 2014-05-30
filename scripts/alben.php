<?php
$alben = "";

$sql = "SELECT ID, titel FROM alben WHERE typ=1 ORDER BY pos";
$qry = $DB_LINK -> query($sql);
if($qry -> num_rows() == 0) {
	$alben = "<p>Es gibt zurzeit keine Alben.</p>";

} else {
	$alben = "<ul class=\"normliste\">\n";
	while($res = $qry -> fetch_assoc()) {
		$alben .= "<li><a href=\"fotos-{$res['ID']}.html\">{$res['titel']}</a></li>\n";
	}
	$alben .= "</ul>\n";
	
}

$platzhalter['alben'] = $alben;
?>