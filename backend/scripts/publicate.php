<?php
$status = '';
$mitteilung = '';
$ID = 0;

$fehlerArr = array();

if($showPage -> checkUG('admin')) {
	
	$ID = (isset($showPage -> arrVars[1])) ? $showPage -> arrVars[1] : 0;
	$sql = "SELECT p.titel, b.anrede, b.nachname, b.email FROM jahresprogramm p INNER JOIN benutzer b ON p.registered_by=b.ID WHERE p.ID='{p}'";
	$qry = $DB_LINK -> query($sql, array($ID));
	if($qry -> num_rows() == 0) { $showPage -> redirect("jp.html"); }
	$res = $qry -> fetch_assoc();

	$titel = $res['titel'];
	$email = $res['email'];
	$nachname = $res['nachname'];
	$anrede = $res['anrede'];
  $mitteilung = "Grüezi\n\nDer von Ihnen erfasste Anlass \"{$titel}\" wurde von einem Administrator geprüft und auf unserer Website {$_SERVER['SERVER_NAME']} veröffentlicht.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

	if(isset($_GET['send'])) {

  	if(!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
  		$fehlerArr[] = 'Sie haben keine Mitteilung geschrieben.';
  	} else {
  		$mitteilung = $_POST['mitteilung'];
  	}

	  if(count($fehlerArr) == 0) {
	  	
	  	$DB_LINK -> query("UPDATE jahresprogramm SET confirmed=NOW(), denied='0000-00-00 00:00:00' WHERE ID='{p}'", array($ID));

  	  $to = $email;
      $toName = $email;
   	  $from = "webmaster@bsv-buelach.ch";
      $fromName = "webmaster@bsv-buelach.ch";
      $subject = 'Ihr Anlass wurde freigeschaltet';
      
      FormMailer::sendMail($to, $toName, $from, $fromName, $subject, $mitteilung, "no", "txt");
      $showPage -> redirect("anlassDet-{$ID}.html");
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