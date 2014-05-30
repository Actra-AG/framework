<?php
$unconfirmed = '';
$tocheck = '';

if($showPage -> checkUG('admin')) {

  $sql = "SELECT b.ID, b.vorname, b.nachname, DATE_FORMAT(b.registered, '%d.%m.%Y %T') AS registered FROM benutzer b WHERE b.accepted='0000-00-00 00:00:00' AND b.denied='0000-00-00 00:00:00' ORDER BY b.registered DESC";
  $qry = $DB_LINK -> query($sql);
	if($qry -> num_rows() == 0) {
		$unconfirmed = "<p>Zurzeit gibt es keine unbestätigten Registrierungen.</p>";

	} else {
		$unconfirmed = "<ul>\n";

		while($res = $qry -> fetch_assoc()) {
			$unconfirmed .= "<li><a href=\"benutzerDet-{$res['ID']}.html\">{$res['vorname']} {$res['nachname']}</a> [{$res['registered']}]</li>\n";

		}
		$unconfirmed .= "</ul>";
	}

  $sql = "SELECT p.ID, p.titel, DATE_FORMAT(p.registered, '%d.%m.%Y %T') AS registered FROM jahresprogramm p WHERE p.confirmed='0000-00-00 00:00:00' AND p.denied='0000-00-00 00:00:00' ORDER BY p.registered DESC";
  $qry = $DB_LINK -> query($sql);
	if($qry -> num_rows() == 0) {
		$tocheck = "<p>Zurzeit gibt es keine zu prüfenden Anlässe.</p>";

	} else {
		$tocheck = "<ul>\n";

		while($res = $qry -> fetch_assoc()) {
			$tocheck .= "<li><a href=\"anlassDet-{$res['ID']}.html\">{$res['titel']}</a> [{$res['registered']}]</li>\n";

		}
		$tocheck .= "</ul>";
	}

}

$platzhalter['unconfirmed'] = $unconfirmed;
$platzhalter['tocheck'] = $tocheck;
?>