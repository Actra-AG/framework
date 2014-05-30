<?php
$backlink = '';
$was = '';
$version = '';
$fields = '';

$fehlerArr = array();

if($showPage -> checkUG('redaktor') && isset($showPage -> arrVars[2])) {
	
	$typ = $showPage -> arrVars[1];
	$ID = $showPage -> arrVars[2];
	
	if($typ == 'seite') {
		
		$sql = "SELECT DATE_FORMAT(s.datum, '%d.%m.%Y %T') AS datum, s.ort, s.seite, s.inhalt, s.config FROM seiteninhalte s WHERE s.ID={p}";
		$qry = $DB_LINK -> query($sql, array($ID));
		$res = $qry -> fetch_assoc();

		$backlink = "seiteArchiv-{$res['ort']}-{$res['seite']}.html";
		$was = "Seite {$res['seite']} ({$res['ort']})";
		$version = $res['datum'];
		
		$srcArr[1] = '<';
		$rplArr[1] = "&lt;";

		$srcArr[2] = '>';
		$rplArr[2] = "&gt;";
		
		$fields .= "<h3>Inhalt</h3>\n<pre>".str_replace($srcArr, $rplArr, $res['inhalt'])."</pre>";
		$fields .= "<h3>Konfiguration</h3>\n<pre>".str_replace($srcArr, $rplArr, $res['config'])."</pre>";

	}
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['backlink'] = $backlink;
$platzhalter['was'] = $was;
$platzhalter['version'] = $version;
$platzhalter['fields'] = $fields;
?>