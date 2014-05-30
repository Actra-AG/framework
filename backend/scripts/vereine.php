<?php
$stichwort = '';
$liste = '';

$fehlerArr = array();

$fn = 'adm_vereine';

if($showPage -> checkUG('admin')) {
  
  if(isset($_GET['remove'])) {
	 $DB_LINK -> query("DELETE FROM vereine WHERE ID='{p}'", array($_GET['remove']));
	}

  $cond = "WHERE 1=1";
  $paramsArr = array();

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
        $condSearchWord[] = "(v.name LIKE '%{p}%')";
        $paramsArr[] = $val;
      }
    }
    if(count($condSearchWord) != 0) { $cond .= ' AND ('.implode(' OR ', $condSearchWord).')'; }
  }

  $fArr['v.ID']['attributes'] = '';
  $fArr['v.ID']['order'] = 1;
  $fArr['v.ID']['ox'] = 'ASC';
  $fArr['v.ID']['value'] = '#';

  $fArr['v.name']['attributes'] = '';
  $fArr['v.name']['order'] = 1;
  $fArr['v.name']['ox'] = 'ASC';
  $fArr['v.name']['value'] = 'Verein';

  $fArr['action']['attributes'] = '';
  $fArr['action']['order'] = 0;
  $fArr['action']['ox'] = '';
  $fArr['action']['value'] = '&nbsp;';

  $pos = 0;
  $ox = "ASC";
  $orderby = "v.name";
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
    vereine v
    
  {$cond}
  ";
  $qry = $DB_LINK -> query($sql, $paramsArr);
  $res = $qry -> fetch_object();
  if($res -> anz == 0) {
    $liste = "<p>Es wurden keine Einträge gefunden.</p>";

  } else {
    $pagination = $showPage -> getPagenavi("vereine", $res -> anz, $pos);
    $liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
    $liste .= $pagination;

    $liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n".$showPage -> dynTableHeader($fArr, $orderby, $ox)."</thead>\n<tbody>\n";

    $sql = "
    SELECT
      v.ID, v.name
      
    FROM
      vereine v
    
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
      $liste .= "<tr><td>{$res -> ID}</td>\n<td>{$res -> name}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"vereinMod-{$res -> ID}.html\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"vereine.html?remove={$res -> ID}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
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

$platzhalter['stichwort'] = $stichwort;
$platzhalter['liste'] = $liste;
?>