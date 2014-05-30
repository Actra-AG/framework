<?php
$status = '';

$ID = 0;
$code = '';

$fehlerArr = array();

if(!$showPage -> checkAccess()) {

  if(isset($showPage -> arrVars[2])) {
  	$ID = (int)$showPage -> arrVars[1];
  	$code = $showPage -> arrVars[2];

  	if($code == md5("bsvpas{$ID}buelach")) {
      if(isset($_GET['send'])) {

    	  if(!isset($_POST['passwort1']) || $_POST['passwort1'] == '') {
      		$fehlerArr[] = "Geben Sie ein Passwort ein.";

    	  } elseif(!isset($_POST['passwort1']) || $_POST['passwort1'] != $_POST['passwort2']) {
      		$fehlerArr[] = "Sie haben nicht zweimal dasselbe Passwort eingegeben.";

     	  } else {
   		    $passwort = md5($_POST['passwort1']);
   		    $sql = "UPDATE benutzer SET passwort='{p}', wronglogin='{p}' WHERE ID='{p}'";
   		    $DB_LINK -> query($sql, array($passwort, 0, $ID));
          $showPage -> redirect("passwortRes.html");
   	    }
      }
	  } else {
	  	$fehlerArr[] = 'Das Passwort kann nicht geändert werden, da ein ungültiger Link eingegeben wurde.';
	  }

 	} else {
  	$fehlerArr[] = "Das Passwort kann nicht geändert werden, da ein ungültiger Link eingegeben wurde.";
 	}

}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= '</ul></div>';
}

$platzhalter['status'] = $status;
$platzhalter['ID'] = $ID;
$platzhalter['code'] = $code;
?>