<?php
$status = '';
$ID = '';
$liste = '';

$fehlerArr = array();

if($showPage -> checkUG('redaktor')) {

	$ID = (isset($showPage -> arrVars[1])) ? $showPage -> arrVars[1] : 0;

  $sql = "
  SELECT
    titel AS album
    
  FROM
    alben
    
  WHERE
   ID={p}";
  $qry = $DB_LINK -> query($sql, array($ID));
  if($qry -> num_rows() != 1) { $showPage -> redirect("alben.html"); }
  $res = $qry -> fetch_assoc();
  $showPage -> pageArr['platzhalter']['title'] = $res['album'];
	$showPage -> pageArr['grundkonf']['navigator']['title'] = $res['album'];

 	if(isset($_GET['add'])) { $status = '<p class="note-pos">Der Eintrag wurde hinzugefügt.</p>'; }
	if(isset($_GET['mod'])) { $status = '<p class="note-pos">Die Änderungen wurden gespeichert.</p>'; }
	if(isset($_GET['del'])) {
		$sql = "SELECT typ FROM fotos WHERE ID='{p}'";
		$qry = $DB_LINK -> query($sql, array($_GET['del']));
		if($qry -> num_rows() == 1) {
			$res = $qry -> fetch_assoc();
			
   		if(file_exists($_SERVER['DOCUMENT_ROOT']."/galerie/foto{$_GET['del']}.{$res['typ']}")) {
   	    unlink($_SERVER['DOCUMENT_ROOT']."/galerie/foto{$_GET['del']}.{$res['typ']}");
 	    }
	    if(file_exists($_SERVER['DOCUMENT_ROOT']."/galerie/orig_foto{$_GET['del']}.{$res['typ']}")) {
        unlink($_SERVER['DOCUMENT_ROOT']."/galerie/orig_foto{$_GET['del']}.{$res['typ']}");
 	    }
	    if(file_exists($_SERVER['DOCUMENT_ROOT']."/galerie/tnfoto{$_GET['del']}.{$res['typ']}")) {
      	unlink($_SERVER['DOCUMENT_ROOT']."/galerie/tnfoto{$_GET['del']}.{$res['typ']}");
 	    }
   		$DB_LINK -> query("DELETE FROM fotos WHERE ID='{p}'", array($_GET['del']));
  		$status = '<p class="note-pos">Der Eintrag wurde gelöscht.</p>';
  	}

	}
	
	if(isset($_GET['up']) || isset($_GET['down'])) {
		$action = (isset($_GET['up'])) ? 'up' : 'down';
		$fotoID = (isset($_GET['up'])) ? $_GET['up'] : $_GET['down'];
    $qry = $DB_LINK -> query("SELECT pos FROM fotos WHERE ID='{p}'", array($fotoID));
    $res = $qry -> fetch_assoc();
    $newPos = $res['pos'];
    if($action == 'up') {
    	$newPos = $res['pos'] - 1.5;
    } elseif($action == "down") {
      $newPos = $res['pos'] + 1.5;
	  }
    $newPos = str_replace(",", ".", $newPos);
   	$DB_LINK -> query("UPDATE fotos SET pos='{p}' WHERE ID='{p}'", array($newPos, $fotoID));

    $i = 0;
  	$qry = $DB_LINK -> query("SELECT ID FROM fotos WHERE albumID='{p}' ORDER BY pos", array($ID));
 	  while($res = $qry -> fetch_assoc($qry)) {
   	  $i++;
 	    $fotoID = $res['ID'];
   	  $DB_LINK -> query("UPDATE fotos SET pos={$i} WHERE ID='{p}'", array($fotoID));
 	  }
	}

  $fn = 'adm_fotos'.$ID;

  $paramsArr = array();
  $cond = "WHERE albumID='{p}'";
  $paramsArr[] = $ID;
  
  $fArr['foto']['attributes'] = '';
  $fArr['foto']['order'] = 0;
  $fArr['foto']['ox'] = '';
  $fArr['foto']['value'] = 'Foto';

  $fArr['text']['attributes'] = '';
  $fArr['text']['order'] = 0;
  $fArr['text']['ox'] = '';
  $fArr['text']['value'] = 'Beschreibung';

  $fArr['groesse']['attributes'] = '';
  $fArr['groesse']['order'] = 0;
  $fArr['groesse']['ox'] = '';
  $fArr['groesse']['value'] = 'Grösse';

  $fArr['position']['attributes'] = '';
  $fArr['position']['order'] = 0;
  $fArr['position']['ox'] = '';
  $fArr['position']['value'] = 'Position';

  $fArr['action']['attributes'] = '';
  $fArr['action']['order'] = 0;
  $fArr['action']['ox'] = '';
  $fArr['action']['value'] = '&nbsp;';

  $pos = 0;
  $ox = "ASC";
  $orderby = "f.pos";
  if(isset($_GET['pos'])) { $_SESSION[$fn]['pos'] = $_GET['pos']; }
  if(isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) { $_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']); }
  if(isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) { $_SESSION[$fn]['ox'] = $_GET['ox']; }
  if(isset($_SESSION[$fn]['pos'])) { $pos = (int)$_SESSION[$fn]['pos']; }
  if(isset($_SESSION[$fn]['orderby'])) { $orderby = $_SESSION[$fn]['orderby']; }
  if(isset($_SESSION[$fn]['ox'])) { $ox = $_SESSION[$fn]['ox']; }
  
  $sql = "
  SELECT
    COUNT(f.ID) AS anz
  
  FROM
    fotos f
  
  {$cond}
  
  ";
  $qry = $DB_LINK -> query($sql, $paramsArr);
  $res = $qry -> fetch_object();
  $anz = $res -> anz;
  if($anz == 0) {
    $liste = "<p>Es wurden keine Einträge gefunden.</p>";

  } else {

    $pagination = $showPage -> getPagenavi("fotos-{$ID}", $anz, $pos);
    $liste = "<p class=\"searchresult\">Es wurde(n) <strong>{$res -> anz}</strong> Resultat(e) gefunden.</p>";
    $liste .= $pagination;

    $liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\" class=\"normtabelle\">\n<thead>\n".$showPage -> dynTableHeader($fArr, $orderby, $ox)."</thead>\n<tbody>\n";

    $sql = "
    SELECT
      f.ID, f.typ, f.text
      
    FROM
      fotos f
      
    {$cond}
    
    ORDER BY
      {p} {p}

    LIMIT
      {p}, {p}";
    $paramsArr[] = $orderby;
    $paramsArr[] = $ox;
    $paramsArr[] = $pos;
    $paramsArr[] = $showPage -> config['lists']['entriesPerPage'];

    $i = 0;
    $qry = $DB_LINK -> query($sql, $paramsArr);
    while($res = $qry -> fetch_assoc()) {
    	
    	$i++;
    	
    	$pArr = array();
    	if($i != 1) { $pArr[] = "<li class=\"oben\"><a href=\"fotos-{$ID}.html?up={$res['ID']}\">nach&nbsp;oben</a></li>"; }
    	if($i != $anz) { $pArr[] = "<li class=\"unten\"><a href=\"fotos-{$ID}.html?down={$res['ID']}\">nach&nbsp;unten</a></li>"; }

    	$size = '';
    	$foto = '';
    	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/foto'.$res['ID'].'.'.$res['typ'])) {

        $size = $showPage -> bytestostring(filesize($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$res['ID'].'.'.$res['typ']));
    		$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$res['ID'].'.'.$res['typ']);
    		$foto = "<a href=\"/galerie/foto{$res['ID']}.{$res['typ']}\"><img src=\"/galerie/tnfoto{$res['ID']}.{$res['typ']}\" {$imgArr[3]} alt=\"\" /></a>";
    	}
      $liste .= "<tr>\n<td>{$foto}</td>\n<td>{$res['text']}</td>\n<td>{$size}</td>\n<td class=\"pos\">";
      if(count($pArr) == 0) {
      	$liste .= "&nbsp;";
      } else {
      	$liste .= "<ul>";
      	foreach($pArr AS $val) {
      		$liste .= $val;
      	}
      	$liste .= "</ul>\n";
      }
      $liste .= "</td>\n<td class=\"aktion\">\n<ul>\n<li><a href=\"fotoMod-{$ID}-{$res['ID']}.html\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"fotos-{$ID}.html?del={$res['ID']}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
    }
    $liste .= "</tbody>\n</table></div>";
    $liste .= $pagination;
  }
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['status'] = $status;
$platzhalter['ID'] = $ID;
$platzhalter['liste'] = $liste;
?>