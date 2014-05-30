<?php
$status = '';
$frontend = '';
$backend = '';

$fehlerArr = array();

$fn = 'adm_seitenfilter';

if($showPage -> checkUG('redaktor')) {

	$oArr['frontend']['pages'] = $_SERVER['DOCUMENT_ROOT'].'/frontend/pages/';
	$oArr['frontend']['config'] = $_SERVER['DOCUMENT_ROOT'].'/frontend/config/';
	$oArr['frontend']['scripts'] = $_SERVER['DOCUMENT_ROOT'].'/scripts/';
	$oArr['frontend']['name'] = "Frontend";

	$oArr['backend']['pages'] = $_SERVER['DOCUMENT_ROOT'].'/backend/pages/';
	$oArr['backend']['config'] = $_SERVER['DOCUMENT_ROOT'].'/backend/config/';
	$oArr['backend']['scripts'] = $_SERVER['DOCUMENT_ROOT'].'/backend/scripts/';
	$oArr['backend']['name'] = "Backend";

  if(isset($_GET['remove']) && isset($showPage -> arrVars[1]) && isset($oArr[$showPage -> arrVars[1]])) {
  	$ort = $showPage -> arrVars[1];
    $path_pages = $oArr[$ort]['pages'];
    $path_config = $oArr[$ort]['config'];
    $path_scripts = $oArr[$ort]['scripts'];
    
    if(file_exists($path_pages."{$_GET['remove']}.html")) { unlink($path_pages."{$_GET['remove']}.html"); }
    if(file_exists($path_config."{$_GET['remove']}.php")) { unlink($path_config."{$_GET['remove']}.php"); }
    if(file_exists($path_scripts."{$_GET['remove']}.php")) { unlink($path_scripts."{$_GET['remove']}.php"); }
	}
	

  $pArr['frontend'] = array();
  $pArr['backend'] = array();

  $handle = opendir("frontend/pages");
  while(false !== ($file = readdir($handle))) {
  	if($file != '.' && $file != '..') {
		  $xArr = explode(".", $file);
		  if(count($xArr) == 2) {
  		  $pArr['frontend'][] = $xArr[0];
  		}
	  }
  }

  $handle = opendir("backend/pages");
  while(false !== ($file = readdir($handle))) {
  	if($file != '.' && $file != '..') {
		  $xArr = explode(".", $file);
		  if(count($xArr) == 2) {
  		  $pArr['backend'][] = $xArr[0];
  		}
	  }
  }
  
  asort($pArr['frontend']);
  asort($pArr['backend']);
  
  $anz = count($pArr['frontend']);
  if($anz == 0) {
  	$frontend = "<p>Es wurden keine Einträge gefunden.</p>";
  } else {
  	$frontend = "<p>Es wurde(n) <strong>{$anz}</strong> Resultat(e) gefunden.</p><div class=\"tablewrap\"><table cellspacing=\"0\" class=\"normtabelle\">\n<thead>\n<tr><th scope=\"col\">Seite</th>\n<th scope=\"col\">&nbsp;</th>\n</tr>\n</thead>\n<tbody>\n";
  	
  	foreach($pArr['frontend'] AS $key => $val) {
      $frontend .= "<tr>\n<td>{$val}.html</td>\n<td class=\"aktion\">\n<ul>\n<li><a href=\"seiteMod-frontend-{$val}.html\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"seiteArchiv-frontend-{$val}.html\">Archiv</a></li>\n<li><a href=\"seiten-frontend.html?remove={$val}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
    }
  	$frontend .= "</tbody>\n</table></div>";
  }

  $anz = count($pArr['backend']);
  if($anz == 0) {
  	$backend = "<p>Es wurden keine Einträge gefunden.</p>";
  } else {
  	$backend = "<p>Es wurde(n) <strong>{$anz}</strong> Resultat(e) gefunden.</p><div class=\"tablewrap\"><table cellspacing=\"0\" class=\"normtabelle\">\n<thead>\n<tr><th scope=\"col\">Seite</th>\n<th scope=\"col\">&nbsp;</th>\n</tr>\n</thead>\n<tbody>\n";
  	
  	foreach($pArr['backend'] AS $key => $val) {
      $backend .= "<tr>\n<td>{$val}.html</td>\n<td class=\"aktion\">\n<ul>\n<li><a href=\"seiteMod-backend-{$val}.html\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"seiteArchiv-backend-{$val}.html\">Archiv</a></li>\n<li><a href=\"seiten-backend.html?remove={$val}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
    }
  	$backend .= "</tbody>\n</table></div>";
  }
}

if(count($fehlerArr) != 0) {
	$status = '<div id="formfehler"><p><strong>Folgende Fehler sind aufgetreten:</strong></p><ul>';
	foreach($fehlerArr as $key => $val)	{
		$status .= '<li>'.$val.'</li>';
	}
	$status .= '</ul></div>';
}

$platzhalter['status'] = $status;
$platzhalter['frontend'] = $frontend;
$platzhalter['backend'] = $backend;
?>