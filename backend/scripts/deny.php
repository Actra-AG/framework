<?php
$status = '';
$mitteilung = '';
$ID = 0;

$fehlerArr = array();

if($showPage -> checkUG('admin')) {
	
	$ID = (isset($showPage -> arrVars[1])) ? $showPage -> arrVars[1] : 0;
	$sql = "SELECT anrede, nachname, email FROM benutzer WHERE ID='{p}'";
	$qry = $DB_LINK -> query($sql, array($ID));
	if($qry -> num_rows() == 0) { $showPage -> redirect("benutzer.html"); }
	$res = $qry -> fetch_assoc();

	$email = $res['email'];
	$nachname = $res['nachname'];
	$anrede = $res['anrede'];
  $mitteilung = "Grüezi\n\nIhr Zugang in unseren passwortgeschützten Bereich unter http://{$_SERVER['SERVER_NAME']}/backend/ wurde leider verweigert. Vielen Dank für Ihr Verständnis.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

	if(isset($_GET['send'])) {

  	if(!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
  		$fehlerArr[] = 'Sie haben keine Mitteilung geschrieben.';
  	} else {
  		$mitteilung = $_POST['mitteilung'];
  	}

	  if(count($fehlerArr) == 0) {
	  	
	  	$DB_LINK -> query("UPDATE benutzer SET aktiv=1, denied=NOW(), accepted='0000-00-00 00:00:00' WHERE ID='{p}'", array($ID));

  	  $to = $email;
      $toName = $email;
   	  $from = "webmaster@bsv-buelach.ch";
      $fromName = "webmaster@bsv-buelach.ch";
      $subject = 'Ihr Zugang wurde verweigert';
      
      FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $mitteilung, "no", "txt");
      $showPage -> redirect("benutzerDet-{$ID}.html");
	  }
	}
}

if(count($fehlerArr) != 0) {
	$status = '<div id="formfehler"><ul>';
	foreach($fehlerArr as $key => $val)	{
		$status .= '<li>'.$val.'</li>';
	}
	$status .= '</ul></div>';
}

$platzhalter['status'] = $status;
$platzhalter['mitteilung'] = $mitteilung;
$platzhalter['ID'] = $ID;
?>