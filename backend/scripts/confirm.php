<?php
$status = '';

if (!isset($showPage->arrVars[2])) {
	$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";

} else {
	$ID = $showPage->arrVars[1];
	$code = $showPage->arrVars[2];

	if ($code != md5("bsvregister{$ID}buelach")) {
		$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";

	} else {
		$sql = "SELECT confirmed, DATE_FORMAT(confirmed, '%d.%m.%Y') AS cf, accepted FROM benutzer WHERE ID=?";
		$qry = $DB_LINK->query($sql, array($ID));
		if ($qry->rowCount() != 1) {
			$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";

		} else {
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			if ($res['confirmed'] == '0000-00-00 00:00:00') {
				$DB_LINK->query("UPDATE benutzer SET confirmed=NOW() WHERE ID=?", array($ID));
				$status = "<p>Ihr Zugang wurde aktiviert.</p>";

			} else {
				$status = "<p class=\"error\">Dieser Zugang wurde bereits am {$res['cf']} aktiviert.</p>";

			}
			if ($res['accepted'] == '0000-00-00 00:00:00') {
				$status .= "<p>Sie werden benachrichtigt, sobald wir Ihren Zugang freigeschaltet haben.</p>";
			}
		}
	}
}

$platzhalter['status'] = $status;
/* EOF */