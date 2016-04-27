<?php
$status = '';
$ort = '';
$seite = '';

$datenArr['text'] = '';
$datenArr['config'] = '';

$fehlerArr = array();

if($showPage -> checkUG('redaktor') && isset($showPage -> arrVars[2])) {
	$benutzerID = (int)$showPage -> userData -> ID;

	$oArr['frontend']['pages'] = $_SERVER['DOCUMENT_ROOT'].'/frontend/pages/';
	$oArr['frontend']['config'] = $_SERVER['DOCUMENT_ROOT'].'/frontend/config/';
	$oArr['frontend']['name'] = "Frontend";

	$oArr['backend']['pages'] = $_SERVER['DOCUMENT_ROOT'].'/backend/pages/';
	$oArr['backend']['config'] = $_SERVER['DOCUMENT_ROOT'].'/backend/config/';
	$oArr['backend']['name'] = "Backend";
	
	$ort = 'frontend'; if(isset($showPage -> arrVars[1]) && isset($oArr[$showPage -> arrVars[1]])) { $ort = $showPage -> arrVars[1]; }

  $path_pages = $oArr[$ort]['pages'];
  $path_config = $oArr[$ort]['config'];
  $ortname = $oArr[$ort]['name'];

	$seite = $showPage -> arrVars[2];
	
	if(!file_exists($path_pages."{$seite}.html") || !file_exists($path_config."{$seite}.php")) {
    $showPage -> redirect("seiten.html");
	}
	
	$datenArr['text'] = file_get_contents($path_pages . $seite . '.html');
	$datenArr['config'] = file_get_contents($path_config . $seite . '.php');

	if(isset($_GET['send'])) {
  	if(isset($_POST['text'])) {
  		$f = fopen($path_pages."{$seite}.html", "w");
		  fwrite($f, stripslashes($_POST['text']));
		  fclose($f);
  	}

  	if(isset($_POST['config'])) {
  		$f = fopen($path_config."{$seite}.php", "w");
		  fwrite($f, stripslashes($_POST['config']));
		  fclose($f);
  	}
  	
  	$paramsArr[] = $benutzerID;
  	$paramsArr[] = $ort;
  	$paramsArr[] = $seite;
  	$paramsArr[] = $_POST['text'];
  	$paramsArr[] = $_POST['config'];
  	
  	$DB_LINK -> query("INSERT INTO seiteninhalte SET benutzerID='{p}', ort='{p}', seite='{p}', inhalt='{p}', config='{p}'", $paramsArr);

	  if(count($fehlerArr) == 0) {
      $showPage -> redirect("seiten.html");
	  }
	}

  $datenArr['text'] = htmlentities($datenArr['text'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
  $datenArr['config'] = htmlentities($datenArr['config'], ENT_COMPAT | ENT_HTML401, 'ISO-8859-1');
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['status'] = $status;
$platzhalter['ort'] = $ort;
$platzhalter['seite'] = $seite;

foreach($datenArr AS $key => $val) { $platzhalter[$key] = $val; }