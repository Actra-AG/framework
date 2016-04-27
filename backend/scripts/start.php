<?php
$unconfirmed = '';
$tocheck = '';

if($showPage -> checkUG('admin')) {

  $sql = "SELECT b.ID, b.vorname, b.nachname, DATE_FORMAT(b.registered, '%d.%m.%Y %T') AS registered FROM benutzer b WHERE b.accepted='0000-00-00 00:00:00' AND b.denied='0000-00-00 00:00:00' ORDER BY b.registered DESC";
  $qry = $DB_LINK -> query($sql);
	if($qry -> rowCount() == 0) {
		$unconfirmed = "<p>Zurzeit gibt es keine unbestätigten Registrierungen.</p>";

	} else {
		$unconfirmed = "<ul>\n";

		while($res = $qry -> fetch(PDO::FETCH_ASSOC)) {
			$href = "benutzerDet-{$res['ID']}.html";
			$unconfirmed .= "<li><a href=\"{$href}\">{$res['vorname']} {$res['nachname']}</a> [{$res['registered']}]</li>\n";

		}
		$unconfirmed .= "</ul>";
	}

  $sql = "SELECT p.ID, p.titel, DATE_FORMAT(p.registered, '%d.%m.%Y %T') AS registered FROM jahresprogramm p WHERE p.confirmed='0000-00-00 00:00:00' AND p.denied='0000-00-00 00:00:00' ORDER BY p.registered DESC";
  $qry = $DB_LINK -> query($sql);
	if($qry -> rowCount() == 0) {
		$tocheck = "<p>Zurzeit gibt es keine zu prüfenden Anlässe.</p>";

	} else {
		$tocheck = "<ul>\n";

		while($res = $qry -> fetch(PDO::FETCH_ASSOC)) {
			$href = "anlassDet-{$res['ID']}.html";
			$tocheck .= "<li><a href=\"{$href}\">{$res['titel']}</a> [{$res['registered']}]</li>\n";

		}
		$tocheck .= "</ul>";
	}

}

$platzhalter['unconfirmed'] = $unconfirmed;
$platzhalter['tocheck'] = $tocheck;
/* EOF */