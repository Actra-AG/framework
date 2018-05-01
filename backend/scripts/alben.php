<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class alben extends pageClass
{
	public function execute()
	{
		$status = '';
		$liste = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {

			if (isset($_GET['add'])) {
				$status = '<p class="note-pos">Der Eintrag wurde hinzugefügt.</p>';
			}
			if (isset($_GET['mod'])) {
				$status = '<p class="note-pos">Die Änderungen wurden gespeichert.</p>';
			}
			if (isset($_GET['del'])) {
				$this->db->query("DELETE FROM alben WHERE ID=?", [$_GET['del']]);
				$status = '<p class="note-pos">Der Eintrag wurde gelöscht.</p>';
			}

			if (isset($_GET['up']) || isset($_GET['down'])) {
				$action = (isset($_GET['up'])) ? 'up' : 'down';
				$albumID = (isset($_GET['up'])) ? $_GET['up'] : $_GET['down'];
				$qry = $this->db->query("SELECT pos FROM alben WHERE ID=?", [$albumID]);
				$res = $qry->fetch(PDO::FETCH_ASSOC);
				$newPos = $res['pos'];
				if ($action == 'up') {
					$newPos = $res['pos'] - 1.5;
				} else if ($action == "down") {
					$newPos = $res['pos'] + 1.5;
				}
				$newPos = str_replace(",", ".", $newPos);
				$this->db->query("UPDATE alben SET pos=? WHERE ID=?", [$newPos, $albumID]);

				$i = 0;
				$qry = $this->db->query("SELECT ID FROM alben ORDER BY pos");
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
					$i++;
					$albumID = $res['ID'];
					$this->db->query("UPDATE alben SET pos={$i} WHERE ID=?", [$albumID]);
				}
			}

			$fn = 'adm_alben';

			$paramsArr = [];
			$cond = "WHERE a.typ=1";

			$fArr['titel']['attributes'] = '';
			$fArr['titel']['order'] = 0;
			$fArr['titel']['ox'] = '';
			$fArr['titel']['value'] = 'Album';

			$fArr['fotos']['attributes'] = '';
			$fArr['fotos']['order'] = 0;
			$fArr['fotos']['ox'] = '';
			$fArr['fotos']['value'] = 'Fotos';

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
			$orderby = "a.pos";
			if (isset($_GET['pos'])) {
				$_SESSION[$fn]['pos'] = $_GET['pos'];
			}
			if (isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) {
				$_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']);
			}
			if (isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) {
				$_SESSION[$fn]['ox'] = $_GET['ox'];
			}
			if (isset($_SESSION[$fn]['pos'])) {
				$pos = (int)$_SESSION[$fn]['pos'];
			}
			if (isset($_SESSION[$fn]['orderby'])) {
				$orderby = $_SESSION[$fn]['orderby'];
			}
			if (isset($_SESSION[$fn]['ox'])) {
				$ox = $_SESSION[$fn]['ox'];
			}

			$sql = "
  SELECT
    COUNT(a.ID) AS anz
  
  FROM
    alben a
  
  "."{$cond}
  
  ";
			$qry = $this->db->query($sql, $paramsArr);
			$res = $qry->fetchObject();
			$anz = $res->anz;
			if ($anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {

				$pagination = $this->showPage->getPagenavi("alben", $anz, $pos);
				$liste = "<p>Es wurde(n) <strong>{$res -> anz}</strong> Resultat(e) gefunden.</p>\n<br />";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\" class=\"normtabelle\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      a.ID, a.titel, (SELECT COUNT(*) FROM fotos WHERE albumID=a.ID) AS fotos
      
    FROM
      alben a
      
    "."{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$i = 0;
				$qry = $this->db->query($sql, $paramsArr);
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

					$i++;

					$pArr = [];
					if ($i != 1) {
						$href = "alben.html?up={$res['ID']}";
						$pArr[] = "<li class=\"oben\"><a href=\"{$href}\">nach&nbsp;oben</a></li>";
					}
					if ($i != $anz) {
						$href = "alben.html?down={$res['ID']}";
						$pArr[] = "<li class=\"unten\"><a href=\"{$href}\">nach&nbsp;unten</a></li>";
					}

					$href = "fotos-{$res['ID']}.html";
					$liste .= "<tr>\n<td>{$res['titel']}</td>\n<td>{$res['fotos']} [<a href=\"{$href}\">verwalten</a>]</td>\n<td class=\"pos\">";
					if (count($pArr) == 0) {
						$liste .= "&nbsp;";
					} else {
						$liste .= "<ul>";
						foreach ($pArr AS $val) {
							$liste .= $val;
						}
						$liste .= "</ul>\n";
					}
					$href1 = "albumMod-{$res['ID']}.html";
					$href2 = "alben.html?del={$res['ID']}";
					$liste .= "</td>\n<td class=\"aktion\">\n<ul>\n<li><a href=\"{$href1}\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"{$href2}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $key => $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['liste'] = $liste;
	}
}


/* EOF */