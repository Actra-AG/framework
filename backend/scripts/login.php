<?php
$status = '';
$loginemail = '';
$loginpasswort = '';

$fehlerArr = array();

$showPage->logOut();

require_once($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'mnauth/check.php');

if (isset($_GET['send']) || $mnauth) {

	if($mnauth) {
		$loginemail = 'entwicklung@metanet.ch';
		$loginpasswort = 'mnauth';

	} else {
		if (!isset($_POST['loginemail']) || $_POST['loginemail'] == '') {
			$fehlerArr[] = 'Geben Sie Ihre E-Mail-Adresse ein.';
		} else {
			$loginemail = $_POST['loginemail'];
		}

		if (!isset($_POST['loginpasswort']) || $_POST['loginpasswort'] == '') {
			$fehlerArr[] = 'Geben Sie Ihr Passwort ein.';
		} else {
			$loginpasswort = $_POST['loginpasswort'];
		}
	}

	if (count($fehlerArr) == 0) {

		$sql = "
  	SELECT
  	  b.ID, b.wronglogin, b.passwort, b.confirmed, b.accepted, b.vorname, b.nachname, b.aktiv, b.admin, b.vorstand, b.redaktor
  	  
  	FROM
  	  benutzer b
  	  
  	WHERE
  	  b.email='{p}'
  	";
		$qry = $DB_LINK->query($sql, array($loginemail));
		$db_passwort = md5($loginpasswort);

		if ($qry->num_rows() != 1) {
			$fehlerArr[] = 'Sie haben ungültige Zugangsdaten eingegeben.';

		} else {
			$personData = $qry->fetch_object();
			if ($personData->confirmed == '0000-00-00 00:00:00') {
				$fehlerArr[] = 'Sie haben Ihre Registrierung noch nicht bestätigt.';

			} elseif ($personData->accepted == '0000-00-00 00:00:00') {
				$fehlerArr[] = 'Ihr Zugang wurde noch nicht durch uns freigeschaltet.';

			} elseif ($personData->aktiv == 0) {
				$fehlerArr[] = 'Dieser Zugang ist leider nicht aktiv.';

			} elseif ($personData->wronglogin >= 10) {
				$fehlerArr[] = 'Bei diesem Konto wurde zehnmal hintereinander das falsche Passwort eingegeben. Falls Sie dies nicht waren, muss jemand anderes versucht haben, sich mit Ihren Zugangsdaten einzuloggen. Bitte geben Sie bei <a href="keinpw.html">Passwort vergessen?</a> Ihre E-Mail-Adresse ein. Sie erhalten dann eine E-Mail mit einem bestimmten Link, wo Sie ein neues Passwort wählen können.';

			} elseif ($personData->passwort != $db_passwort && !$mnauth) {
				$fehlerArr[] = 'Sie haben ungültige Zugangsdaten eingegeben.';
				$DB_LINK->query("UPDATE benutzer SET wronglogin=wronglogin+1 WHERE ID={p}", array($personData->ID));

			} else {
				$ip = '';
				if (isset($_SERVER['REMOTE_ADDR'])) {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$DB_LINK->query("UPDATE benutzer SET lastlogin=NOW(), wronglogin=0, visits=visits+1 WHERE ID={p}",
					array($personData->ID));
				$DB_LINK->query("INSERT INTO visits SET benutzerID='{p}', sessionID='{p}', ip='{p}'",
					array($personData->ID, session_id(), $ip));

				$vArr = array();
				$sql = "SELECT vereinID FROM benutzervereine WHERE benutzerID={p}";
				$qry = $DB_LINK->query($sql, array($personData->ID));
				while ($res = $qry->fetch_assoc()) {
					$vArr[] = $res['vereinID'];
				}
				$personData->vereine = $vArr;

				unset($personData->wronglogin);
				unset($personData->passwort);

				$_SESSION = array();
				$_SESSION['userData'] = $personData;
				$_SESSION['intAccess'] = true;

				$requestHandler->regenerate_sessionID();

				$showPage->redirect("start.html");
			}
		}
	}
}

if (count($fehlerArr) != 0) {
	$status = "<div id=\"formfehler\"><ul>\n";
	foreach ($fehlerArr as $key => $val) {
		$status .= "<li>{$val}</li>\n";
	}
	$status .= '</ul></div>';
}

$platzhalter['loginemail'] = $loginemail;
$platzhalter['loginpasswort'] = $loginpasswort;
$platzhalter['status'] = $status;
?>