<?php
$status = '';
$ort = '';
$ortname = '';
$seite = '';

$fehlerArr = array();

if ($showPage->checkUG('redaktor')) {
	$benutzerID = (int)$showPage->userData->ID;

	$oArr['frontend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/pages/';
	$oArr['frontend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/config/';
	$oArr['frontend']['name'] = "Frontend";

	$oArr['backend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/pages/';
	$oArr['backend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/config/';
	$oArr['backend']['name'] = "Backend";

	$ort = 'frontend';
	if (isset($showPage->arrVars[1]) && isset($oArr[$showPage->arrVars[1]])) {
		$ort = $showPage->arrVars[1];
	}

	$path_pages = $oArr[$ort]['pages'];
	$path_config = $oArr[$ort]['config'];
	$ortname = $oArr[$ort]['name'];

	if (isset($_GET['send'])) {

		if (!isset($_POST['seite']) || $_POST['seite'] == '') {
			$fehlerArr[] = 'Sie haben keine Adresse eingegeben.';
		} else {
			$seite = $_POST['seite'];
			if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['seite'])) {
				$fehlerArr[] = 'Die Adresse darf keine Sonder- und Leerzeichen beinhalten.';
			} elseif (file_exists($path_pages . "{$seite}.html") || file_exists($path_config . "{$seite}.php")) {
				$fehlerArr[] = 'Diese Adresse existiert bereits.';
			}
		}

		if (count($fehlerArr) == 0) {
			$f = fopen($path_pages . "{$seite}.html", "w");
			fclose($f);
			chmod($path_pages . "{$seite}.html", 0777);

			$f = fopen($path_config . "{$seite}.php", "w");
			fwrite($f, '<?php' . "\n");
			fwrite($f, '//Grundkonfiguration' . "\n");
			fwrite($f, '$grundkonf[\'templateID\'] = \'1\';' . "\n\n");
			fwrite($f, '//Platzhalter' . "\n");
			fwrite($f, '$platzhalter[\'title\'] = \'SEITENTITEL\';' . "\n");
			fwrite($f, '$platzhalter[\'description\'] = \'description\';' . "\n\n");
			fwrite($f, '// Zusatzcode in Header' . "\n");
			fwrite($f, '$platzhalter[\'scripts\'] = \'\';' . "\n\n");
			fwrite($f, '// Navigation' . "\n");
			fwrite($f, '$navistufe[1] = \'\';' . "\n");
			fwrite($f, '/* EOF */');
			fclose($f);
			chmod($path_config . "{$seite}.php", 0777);

			$showPage->redirect("seiteMod-{$ort}-{$seite}.html");
		}
	}
}

if (count($fehlerArr) != 0) {
	$status = "<div id=\"formfehler\"><ul>\n";
	foreach ($fehlerArr as $key => $val) {
		$status .= "<li>{$val}</li>\n";
	}
	$status .= "</ul></div>";
}

$platzhalter['status'] = $status;
$platzhalter['ort'] = $ort;
$platzhalter['ortname'] = $ortname;
$platzhalter['seite'] = $seite;
/* EOF */