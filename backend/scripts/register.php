<?php
$bsvb = new bsvb();

$status = '';
$Herr = '';
$Frau = '';
$vereine = '';

$datenArr['vereinID'] = '';
$datenArr['anrede'] = '';
$datenArr['vorname'] = '';
$datenArr['nachname'] = '';
$datenArr['strasse'] = '';
$datenArr['plz'] = '';
$datenArr['ort'] = '';
$datenArr['lizenz'] = '';
$datenArr['telefon'] = '';
$datenArr['email'] = '';
$datenArr['kommentar'] = '';
$datenArr['passwort'] = '';

$fehlerArr = array();

if(!$showPage -> checkAccess()) {
  $vArr = array();

  $vArr[0] = 'keiner';
  $sql = "SELECT ID, name FROM vereine ORDER BY name";
  $qry = $DB_LINK -> query($sql);
  while($res = $qry -> fetch_assoc()) {
  	$vArr[$res['ID']] = $res['name'];
  	
  }


  if(isset($_GET['send'])) {

    $vc = '';
    if(!isset($_POST['vereinID']) || !isset($vArr[$_POST['vereinID']])) {
	 	  $fehlerArr[] = "Wählen Sie einen Verein aus";

	  } else {
  		$datenArr['vereinID'] = $_POST['vereinID'];
		  $vc = $vArr[$_POST['vereinID']];

	  }
	
  	if(!isset($_POST['anrede'])) {
		  $fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';

	  } elseif($_POST['anrede'] != 'Herr' && $_POST['anrede'] != 'Frau') {
  		$fehlerArr[] = 'Bitte wählen Sie eine Anrede aus.';
  	
  	} else {
		  $datenArr['anrede'] = $_POST['anrede'];

	  }
	
  	if(!isset($_POST['vorname']) || trim($_POST['vorname']) == '') {
		  $fehlerArr[] = "Geben Sie bitte Ihren Vornamen an.";

  	} else {
		  $datenArr['vorname'] = $_POST['vorname'];

	  }

	  if(!isset($_POST['nachname']) || trim($_POST['nachname']) == '') {
		  $fehlerArr[] = "Geben Sie bitte Ihren Nachnamen an.";

	  } else {
  		$datenArr['nachname'] = $_POST['nachname'];

	  }

  	if(!isset($_POST['strasse']) || trim($_POST['strasse']) == '') {
		  $fehlerArr[] = "Geben Sie bitte Ihre Strasse an.";

	  } else {
  		$datenArr['strasse'] = $_POST['strasse'];

  	}

  	if(!isset($_POST['plz']) || trim($_POST['plz']) == '') {
		  $fehlerArr[] = "Geben Sie bitte die PLZ an.";

	  } else {
  		$datenArr['plz'] = $_POST['plz'];

  	}

  	if(!isset($_POST['ort']) || trim($_POST['ort']) == '') {
		  $fehlerArr[] = "Geben Sie bitte den Ort an.";
	
	  } else {
  		$datenArr['ort'] = $_POST['ort'];

  	}

	  if(isset($_POST['lizenz'])) { $datenArr['lizenz'] = $_POST['lizenz']; }
	  if(isset($_POST['telefon'])) { $datenArr['telefon'] = $_POST['telefon']; }

  	if(!isset($_POST['email']) || $_POST['email'] == '') {
		  $fehlerArr[] = 'Geben Sie bitte eine E-Mail-Adresse an.';

	  } elseif(!$showPage -> valemail($_POST['email'])) {
  		$fehlerArr[] = 'Geben Sie bitte eine gültige E-Mail-Adresse an.';

  	} else {
	    $datenArr['email'] = $_POST['email'];
	    $sql = "SELECT COUNT(*) AS anz FROM benutzer WHERE email='{p}'";
	    $qry = $DB_LINK -> query($sql, array($_POST['email']));
	    $res = $qry -> fetch_assoc();
	    if($res['anz'] != 0) {
   			$fehlerArr[] = 'Die eingegebene E-Mail-Adresse ist bereits registriert. Geben Sie bitte eine andere ein.';

		  }
	  }
	
	  if(isset($_POST['kommentar'])) { $datenArr['kommentar'] = $_POST['kommentar']; }

	  if(!isset($_POST['passwort']) || $_POST['passwort'] == '') {
  		$fehlerArr[] = 'Sie haben kein Passwort eingegeben.';

  	} elseif(!isset($_POST['passwort_confirm']) || $_POST['passwort'] != $_POST['passwort_confirm']) {
		  $fehlerArr[] = 'Geben Sie bitte zweimal dasselbe Passwort ein.';

	  } else {
  		$datenArr['passwort'] = $_POST['passwort'];
 
  	}
	
  	if(count($fehlerArr) == 0) {
      $datenArr['ip'] = ''; if(isset($_SERVER['REMOTE_ADDR'])) { $datenArr['ip'] = $_SERVER['REMOTE_ADDR']; }
      $datenArr['passwort'] = md5($datenArr['passwort']);
      
      $ID = $bsvb -> insertEntry('benutzer', $datenArr);
      
   	  $to = $_POST['email'];
      $toName = $_POST['email'];
   	  $from = "webmaster@bsv-buelach.ch";
      $fromName = "webmaster@bsv-buelach.ch";
      $subject = "Ihre Registrierung bei {$_SERVER['SERVER_NAME']}";
      $addinfo = "no"; // or "yes"
      
      $code = md5("bsvregister{$ID}buelach");

      $text = "Grüezi\n\nSie haben sich bei {$_SERVER['SERVER_NAME']} registriert. Bitte klicken Sie auf den folgenden Link, um dies zu bestätigen:\n\n{$showPage -> config['protocol']}://{$_SERVER['SERVER_NAME']}/backend/confirm-{$ID}-{$code}.html\n\nWenn Sie sich nicht registriert haben, ignorieren Sie diese E-Mail und klicken Sie nicht auf den obigen Link!\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";
      
      FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $text, "no", "txt");

   	  $to = "webmaster@bsv-buelach.ch";
      $toName = "webmaster@bsv-buelach.ch";
   	  $from = "webmaster@bsv-buelach.ch";
      $fromName = "webmaster@bsv-buelach.ch";
      $subject = "Neue Registrierung bei {$_SERVER['SERVER_NAME']}";
      $addinfo = "no"; // or "yes"
      
      $text = "Grüezi\n\nEs gibt eine neue Registrierung bei {$_SERVER['SERVER_NAME']}. Bitte prüfen Sie diese und akzeptieren oder verweigern Sie den Zugriff.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";
      
      FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $text, "no", "txt");
//      FormMailer::sendMail("info@actra.ch", $toName, $from, $fromName, $subject, $text, "no", "txt");

      $showPage -> redirect("registerRes.html");
    }
	}
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>";
  foreach($fehlerArr as $key => $val)	{
	  $status .= '<li>'.$val.'</li>';
	}
  $status .= '</ul></div>';
}

foreach($datenArr AS $key => $val) {
	if($key == 'anrede') {
		$$val = ' checked="checked"';

	} else {
  	$platzhalter[$key] = $val;
  }
}

foreach($vArr AS $key => $val) {
 	$vereine .= "<option value=\"{$key}\"";
  if($key == $datenArr['vereinID']) { $vereine .= ' selected="selected"'; }
  $vereine .= ">{$val}</option>\n";
}

$platzhalter['status'] = $status;
$platzhalter['Herr'] = $Herr;
$platzhalter['Frau'] = $Frau;
$platzhalter['vereine'] = $vereine;
?>