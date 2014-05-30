<?php
$bsvb = new bsvb();

$vereine = '';
$stichwort = '';
$liste = '';

$fehlerArr = array();

$fn = 'adm_benutzer';

if($showPage -> checkUG('admin')) {

  $vArr = array();

  $vArr[0] = 'keine Einschränkungen';
  $sql = "SELECT ID, name FROM vereine ORDER BY name";
  $qry = $DB_LINK -> query($sql);
  while($res = $qry -> fetch_assoc()) {
  	$vArr[$res['ID']] = $res['name'];
  	
  }

  if(isset($_GET['remove'])) {
	 $DB_LINK -> query("DELETE FROM benutzer WHERE ID='{p}'", array($_GET['remove']));
	}

  $cond = "WHERE 1=1";
  $paramsArr = array();

	if(isset($_POST['vereinID']) && isset($vArr[$_POST['vereinID']])) { $_SESSION[$fn]['vereinID'] = $_POST['vereinID']; }
	if(!isset($_SESSION[$fn]['vereinID'])) { $_SESSION[$fn]['vereinID'] = 0; }
	$vereinID = $_SESSION[$fn]['vereinID'];
	if($vereinID != 0) {
		$cond .= " AND b.ID IN (SELECT benutzerID FROM benutzervereine WHERE vereinID='{p}')";
		$paramsArr[] = $vereinID;
	}
  
  if(isset($_POST['stichwort'])) {
    $_SESSION[$fn]['stichwort'] = $_POST['stichwort'];
    $_SESSION[$fn]['pos'] = 0;
  }

  if(isset($_SESSION[$fn]['stichwort'])) {
    $stichwort = $_SESSION[$fn]['stichwort'];
  }
  
  if(strlen(trim($stichwort)) != 0) {
    $condSearchWord = array();

    $sArr = explode(" ", $stichwort);
    foreach($sArr AS $key => $val) {
      $sx = trim($val);
      if($sx != '') {
        $condSearchWord[] = "(b.vorname LIKE '%{p}%' OR b.nachname LIKE '%{p}%' OR b.email LIKE '%{p}%')";
        $paramsArr[] = $val; $paramsArr[] = $val; $paramsArr[] = $val;
      }
    }
    if(count($condSearchWord) != 0) { $cond .= ' AND ('.implode(' OR ', $condSearchWord).')'; }
  }

  $fArr['b.vorname']['attributes'] = '';
  $fArr['b.vorname']['order'] = 1;
  $fArr['b.vorname']['ox'] = 'ASC';
  $fArr['b.vorname']['value'] = 'Vorname';

  $fArr['b.nachname']['attributes'] = '';
  $fArr['b.nachname']['order'] = 1;
  $fArr['b.nachname']['ox'] = 'ASC';
  $fArr['b.nachname']['value'] = 'Nachname';

  $fArr['b.email']['attributes'] = '';
  $fArr['b.email']['order'] = 1;
  $fArr['b.email']['ox'] = 'ASC';
  $fArr['b.email']['value'] = 'E-Mail';

  $fArr['b.aktiv']['attributes'] = '';
  $fArr['b.aktiv']['order'] = 1;
  $fArr['b.aktiv']['ox'] = 'DESC';
  $fArr['b.aktiv']['value'] = 'Aktiv';

  $fArr['b.admin']['attributes'] = '';
  $fArr['b.admin']['order'] = 1;
  $fArr['b.admin']['ox'] = 'DESC';
  $fArr['b.admin']['value'] = 'Admin';

  $fArr['b.ehren']['attributes'] = '';
  $fArr['b.ehren']['order'] = 1;
  $fArr['b.ehren']['ox'] = 'DESC';
  $fArr['b.ehren']['value'] = 'Ehren.';

  $fArr['b.vorstand']['attributes'] = '';
  $fArr['b.vorstand']['order'] = 1;
  $fArr['b.vorstand']['ox'] = 'DESC';
  $fArr['b.vorstand']['value'] = 'Vorstand';

  $fArr['co']['attributes'] = '';
  $fArr['co']['order'] = 1;
  $fArr['co']['ox'] = 'ASC';
  $fArr['co']['value'] = 'Status';

  $fArr['action']['attributes'] = '';
  $fArr['action']['order'] = 0;
  $fArr['action']['ox'] = '';
  $fArr['action']['value'] = '&nbsp;';

  $pos = 0;
  $ox = "ASC";
  $orderby = "nachname,vorname";
  if(isset($_GET['pos'])) { $_SESSION[$fn]['pos'] = $_GET['pos']; }
  if(isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) { $_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']); }
  if(isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) { $_SESSION[$fn]['ox'] = $_GET['ox']; }
  if(isset($_SESSION[$fn]['pos'])) { $pos = (int)$_SESSION[$fn]['pos']; }
  if(isset($_SESSION[$fn]['orderby'])) { $orderby = $_SESSION[$fn]['orderby']; }
  if(isset($_SESSION[$fn]['ox'])) { $ox = $_SESSION[$fn]['ox']; }

  $sql = "
  SELECT
    COUNT(*) AS anz
    
  FROM
    benutzer b
    
  {$cond}
  ";
  $qry = $DB_LINK -> query($sql, $paramsArr);
  $res = $qry -> fetch_object();
  if($res -> anz == 0) {
    $liste = "<p>Es wurden keine Einträge gefunden.</p>";

  } else {
    $pagination = $showPage -> getPagenavi("benutzer", $res -> anz, $pos);
    $liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
    $liste .= $pagination;

    $liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n".$showPage -> dynTableHeader($fArr, $orderby, $ox)."</thead>\n<tbody>\n";

    $sql = "
    SELECT
      b.ID, b.vorname, b.nachname, b.email, IF(b.aktiv=1, 'ja', 'nein') AS aktiv, IF(b.admin=1, 'ja', 'nein') AS admin, IF(b.ehren=1, 'ja', 'nein') AS ehren, IF(b.vorstand=1, 'ja', 'nein') AS vorstand, IF(b.confirmed='0000-00-00 00:00:00', 'Aktivierung ausstehend', IF(b.accepted!='0000-00-00 00:00:00', 'akzeptiert', IF(b.denied!='0000-00-00 00:00:00', 'abgelehnt', 'zu prüfen'))) AS co
      
    FROM
      benutzer b
    
    {$cond}
    
    ORDER BY
      {p} {p}

    LIMIT
      {p}, {p}";
    $paramsArr[] = $orderby;
    $paramsArr[] = $ox;
    $paramsArr[] = $pos;
    $paramsArr[] = $showPage -> config['lists']['entriesPerPage'];

    $qry = $DB_LINK -> query($sql, $paramsArr);
    while($res = $qry -> fetch_object()) {
      $liste .= "<tr><td>{$res -> vorname}</td>\n<td>{$res -> nachname}</td>\n<td>{$res -> email}</td>\n<td>{$res -> aktiv}</td>\n<td>{$res -> admin}</td>\n<td>{$res -> ehren}</td>\n<td>{$res -> vorstand}</td>\n<td>{$res -> co}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"benutzerDet-{$res -> ID}.html\" class=\"edit\">Details</a></li>\n<li><a href=\"benutzer.html?remove={$res -> ID}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
    }
    $liste .= "</tbody>\n</table></div>";
    $liste .= $pagination;
  }


 	foreach($vArr AS $key => $val) {
	  $vereine .= "<option value=\"{$key}\"";
	  if($key == $vereinID) { $vereine .= ' selected="selected"'; }
 	  $vereine .= ">{$val}</option>\n";
  }
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['vereine'] = $vereine;
$platzhalter['stichwort'] = $stichwort;
$platzhalter['liste'] = $liste;
?>