<?php
$bsvb = new bsvb();

$status = '';
$albumID = '';
$ID = '';
$foto = '';

$datenArr['text'] = '';
$datenArr['typ'] = '';

$fehlerArr = array();

if($showPage -> checkUG('redaktor')) {

	$albumID = (isset($showPage -> arrVars[1])) ? $showPage -> arrVars[1] : 0;
	$ID = (isset($showPage -> arrVars[2])) ? $showPage -> arrVars[2] : 0;

  $sql = "
  SELECT
    titel AS album

  FROM
    alben

  WHERE
   ID='{p}'";
  $qry = $DB_LINK -> query($sql, array($albumID));
  if($qry -> num_rows() != 1) { $showPage -> redirect("alben.html"); }
  $res = $qry -> fetch_assoc();

	$sql = "SELECT text, typ FROM fotos WHERE ID='{p}'";
  $qry = $DB_LINK -> query($sql, array($ID));
	if($qry -> num_rows() == 1) {
		$showPage -> pageArr['platzhalter']['title'] = 'Foto bearbeiten';
		$showPage -> pageArr['grundkonf']['navigator']['title'] = 'Foto bearbeiten';
		$ac = 'mod';
		$datenArr = $qry -> fetch_assoc();

	} else {

		$ID = 0;
		$ac = 'add';
		$showPage -> pageArr['platzhalter']['title'] = 'Foto hinzufügen';
		$showPage -> pageArr['grundkonf']['navigator']['title'] = 'Foto hinzufügen';

	}

 	if($ID == 0) {
		$tempID = session_id();
 	} else {
 		$tempID = $ID;
 	}

	if(isset($_GET['send'])) {

    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
     	$imgArr = getimagesize($_FILES['foto']['tmp_name']);
     	$mime = strtolower($imgArr['mime']);
     	$sql = "SELECT extension FROM dateiformate WHERE mimetype='{p}' AND FIND_IN_SET('foto', arten)!=0";
     	$qry = $DB_LINK -> query($sql, array($mime));
     	if($qry -> num_rows() != 1) {
     		$fehlerArr[] = 'Leider ist das Foto in einem ungültigen Dateiformat ('.$mime.').';

     	} else {
     		$res = $qry -> fetch_assoc();
     		$datenArr['typ'] = $res['extension'];

     		$h = 90;
     		$w = 125;
/*     		if($imgArr[0] < $imgArr[1]) {
     			$h = 160;
     			$w = 120;
     		}*/

        $imgRes = new ImageResize();
        $imgRes -> resize_image($_FILES['foto']['tmp_name'], $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'].'/galerie/', 'foto'.$tempID, 510, 0, 90, 0, 1);
        $imgRes -> resize_image($_SERVER['DOCUMENT_ROOT'].'/galerie/orig_foto'.$tempID.'.'.$datenArr['typ'], $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'].'/galerie/', 'tnfoto'.$tempID, $w, $h, 90, 1, 0);
        if($ac == 'mod') { $DB_LINK -> query("UPDATE fotos SET typ='{p}' WHERE ID='{p}'", array($datenArr['typ'], $tempID)); }
     	}
    } elseif($ac == 'add') {
    	$fehlerArr[] = 'Sie haben kein Foto ausgewählt.';

    }

  	if(isset($_POST['text'])) { $datenArr['text'] = $_POST['text']; }

	  if(count($fehlerArr) == 0) {

    	if($ID == 0) {
	  		$sql = "SELECT MAX(pos)+1 AS pos FROM fotos WHERE albumID='{p}'";
	  		$qry = $DB_LINK -> query($sql, array($albumID));
	  		$res = $qry -> fetch_assoc();
	  		$datenArr['albumID'] = $albumID;
	  		$datenArr['pos'] = $res['pos'];

    		$ID = $bsvb -> insertEntry('fotos', $datenArr);

	  		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/foto'.$tempID.'.'.$datenArr['typ'])) { rename($_SERVER['DOCUMENT_ROOT'].'/galerie/foto'.$tempID.'.'.$datenArr['typ'], $_SERVER['DOCUMENT_ROOT'].'/galerie/foto'.$ID.'.'.$datenArr['typ']); }
	  		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/orig_foto'.$tempID.'.'.$datenArr['typ'])) { rename($_SERVER['DOCUMENT_ROOT'].'/galerie/orig_foto'.$tempID.'.'.$datenArr['typ'], $_SERVER['DOCUMENT_ROOT'].'/galerie/orig_foto'.$ID.'.'.$datenArr['typ']); }
	  		if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$tempID.'.'.$datenArr['typ'])) { rename($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$tempID.'.'.$datenArr['typ'], $_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$ID.'.'.$datenArr['typ']); }

    	} else {
    		$bsvb -> updateEntry('fotos', $ID, $datenArr);

      }

      $showPage -> redirect("fotos-{$albumID}-{$ID}.html?{$ac}");
	  }
	}

  $fototitel = 'Foto';
  $fotozusatz = '';
  if(file_exists($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$tempID.'.'.$datenArr['typ'])) {
  	$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'].'/galerie/tnfoto'.$tempID.'.'.$datenArr['typ']);
	  $foto = "<dl><dt>Aktuelles Foto</dt><dd><img src=\"/galerie/tnfoto{$tempID}.{$datenArr['typ']}?time=".time()."\" {$imgArr[3]} alt=\"\" /> <!--<input type=\"submit\" class=\"submit\" name=\"fotoDel\" value=\"l&ouml;schen\" />--></dd>\n</dl>";
    $fototitel = 'Neues Foto';
    $fotozusatz = " Lassen Sie dieses Feld leer, wenn Sie das bestehende Foto behalten möchten.";
  }
  $foto .= "<dl><dt><label for=\"pfoto\">{$fototitel}</label></dt><dd><input type=\"file\" class=\"file\" name=\"foto\" id=\"pfoto\" />\n<em>Kann im *.jpg, *.gif oder *.png-Format sein. Wird automatisch verkleinert und zugeschnitten.{$fotozusatz}</em></dd><!--<input type=\"submit\" class=\"submit\" name=\"fotoAdd\" value=\"hochladen\" />--></dl>";
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['status'] = $status;
$platzhalter['albumID'] = $albumID;
$platzhalter['ID'] = $ID;
$platzhalter['foto'] = $foto;

foreach($datenArr AS $key => $val) { $platzhalter[$key] = htmlentities($val); }
?>