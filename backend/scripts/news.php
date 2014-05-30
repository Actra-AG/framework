<?php
$archivwahl = '';
$stichwort = '';
$liste = '';

$fehlerArr = array();

$fn = 'adm_news';

if($showPage -> checkUG('redaktor')) {
  
	$archivArr['alle'] = 'Keine Einschränkungen';
	$archivArr['x0'] = 'nein';
	$archivArr['x1'] = 'ja';

  if(isset($_GET['remove'])) {
	 $DB_LINK -> query("DELETE FROM news WHERE ID='{p}'", array($_GET['remove']));
	}

  $cond = "WHERE n.typ=1";
  $paramsArr = array();

	if(isset($_POST['archiv']) && isset($archivArr[$_POST['archiv']])) { $_SESSION[$fn]['archiv'] = $_POST['archiv']; }
	if(!isset($_SESSION[$fn]['archiv'])) { $_SESSION[$fn]['archiv'] = 'alle'; }
	$archiv = $_SESSION[$fn]['archiv'];
	if($archiv != 'alle') {
		$cond .= " AND n.archiv={p}";
		$paramsArr[] = str_replace('x', '', $archiv);
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
        $condSearchWord[] = "(n.titel LIKE '%{p}%' OR n.teaser LIKE '%{p}%' OR n.text LIKE '%{p}%')";
        $paramsArr[] = $val; $paramsArr[] = $val; $paramsArr[] = $val;
      }
    }
    if(count($condSearchWord) != 0) { $cond .= ' AND ('.implode(' OR ', $condSearchWord).')'; }
  }

  $fArr['datum']['attributes'] = '';
  $fArr['datum']['order'] = 0;
  $fArr['datum']['ox'] = '';
  $fArr['datum']['value'] = 'Datum';

  $fArr['titel']['attributes'] = '';
  $fArr['titel']['order'] = 0;
  $fArr['titel']['ox'] = '';
  $fArr['titel']['value'] = 'Titel';

  $fArr['name']['attributes'] = '';
  $fArr['name']['order'] = 0;
  $fArr['name']['ox'] = '';
  $fArr['name']['value'] = 'Erfasser';

  $fArr['archiv']['attributes'] = '';
  $fArr['archiv']['order'] = 0;
  $fArr['archiv']['ox'] = '';
  $fArr['archiv']['value'] = 'Archiv';

  $fArr['action']['attributes'] = '';
  $fArr['action']['order'] = 0;
  $fArr['action']['ox'] = '';
  $fArr['action']['value'] = '&nbsp;';

  $pos = 0;
  $ox = "DESC";
  $orderby = "n.datum";
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
    news n
    
  {$cond}
  ";
  $qry = $DB_LINK -> query($sql, $paramsArr);
  $res = $qry -> fetch_object();
  if($res -> anz == 0) {
    $liste = "<p>Es wurden keine Einträge gefunden.</p>";

  } else {
    $pagination = $showPage -> getPagenavi("news", $res -> anz, $pos);
    $liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
    $liste .= $pagination;

    $liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n".$showPage -> dynTableHeader($fArr, $orderby, $ox)."</thead>\n<tbody>\n";

    $sql = "
    SELECT
      n.ID, DATE_FORMAT(n.datum, '%d.%m.%Y') AS datum, n.titel, IF(n.archiv=1, 'ja', 'nein') AS archiv, CONCAT(b.vorname, ' ', b.nachname) AS name
      
    FROM
      news n
      LEFT JOIN benutzer b ON n.registered_by=b.ID
    
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
      $liste .= "<tr><td>{$res -> datum}</td>\n<td>{$res -> titel}</td>\n<td>{$res -> name}</td>\n<td>{$res -> archiv}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"newsMod-{$res -> ID}.html\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"news.html?remove={$res -> ID}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
    }
    $liste .= "</tbody>\n</table></div>";
    $liste .= $pagination;
  }


 	foreach($archivArr AS $key => $val) {
	  $archivwahl .= "<option value=\"{$key}\"";
	  if($key == $archiv) { $archivwahl .= ' selected="selected"'; }
 	  $archivwahl .= ">{$val}</option>\n";
  }
}

if(count($fehlerArr) != 0) {
  $status = "<div id=\"formfehler\"><ul>\n";
  foreach($fehlerArr as $key => $val)	{
	  $status .= "<li>{$val}</li>\n";
	}
  $status .= "</ul></div>";
}

$platzhalter['archivwahl'] = $archivwahl;
$platzhalter['stichwort'] = $stichwort;
$platzhalter['liste'] = $liste;
?>