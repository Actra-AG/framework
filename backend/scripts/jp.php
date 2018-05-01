<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class jp extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$stichwort = '';
		$typen = '';
		$vereine = '';
		$liste = '';

		$jpArr = $bsvb->getJahresprogramm();

		$fn = 'adm_jp';

		if ($this->showPage->checkUG('aktiv')) {

			$myID = $this->showPage->userData->ID;

			$vArr = [];

			$vArr['alle'] = 'Keine Einschränkungen';
			//  if($this->showPage -> checkUG('admin')) {
			$vArr[0] = "Ohne Zuteilung";
			//  }

			$sql = "SELECT ID, name FROM vereine WHERE ID IN (SELECT vereinID FROM benutzervereine WHERE benutzerID=?) ORDER BY name";
			$qry = $this->db->query($sql, [$myID]);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$vArr[$res['ID']] = $res['name'];
			}

			if (isset($_GET['remove'])) {
				$this->db->query("DELETE FROM jahresprogramm WHERE ID=? AND (registered_by=? OR {$this->showPage->userData->admin}=1)", [
					$_GET['remove'], $myID,
				]);
			}

			if (isset($_GET['showall']) && isset($_SESSION[$fn])) {
				unset($_SESSION[$fn]);
			}

			$cond = "WHERE (j.registered_by=? OR {$this->showPage->userData->admin}=1)";
			$paramsArr[] = $myID;

			if (isset($_POST['typwahl']) && ($_POST['typwahl'] == 'alle' || isset($jpArr['typen'][$_POST['typwahl']]))) {
				$_SESSION[$fn]['typ'] = $_POST['typwahl'];
			}
			if (!isset($_SESSION[$fn]['typ'])) {
				$_SESSION[$fn]['typ'] = 'alle';
			} // key($jpArr['typen'])
			$typ = $_SESSION[$fn]['typ'];

			if ($typ != 'alle') {
				$cond .= " AND j.{$typ}=1";
			}

			if (isset($_POST['vereinwahl']) && isset($vArr[$_POST['vereinwahl']])) {
				$_SESSION[$fn]['vereinID'] = $_POST['vereinwahl'];
			}
			if (!isset($_SESSION[$fn]['vereinID'])) {
				$_SESSION[$fn]['vereinID'] = key($vArr);
			}
			$vereinID = $_SESSION[$fn]['vereinID'];

			if ($vereinID != 'alle') {
				$cond .= " AND j.vereinID=?";
				$paramsArr[] = $vereinID;
			}

			if (isset($_POST['stichwort'])) {
				$_SESSION[$fn]['stichwort'] = $_POST['stichwort'];
				$_SESSION[$fn]['pos'] = 0;
			}

			if (isset($_SESSION[$fn]['stichwort'])) {
				$stichwort = $_SESSION[$fn]['stichwort'];
			}

			if (strlen(trim($stichwort)) != 0) {
				$condSearchWord = [];

				$sArr = explode(" ", $stichwort);
				foreach ($sArr AS $key => $val) {
					$sx = trim($val);
					if ($sx != '') {
						$condSearchWord[] = "(j.titel LIKE ? OR j.ort LIKE ? OR j.bemerkungen LIKE ?)";
						$paramsArr[] = '%' . $val . '%';
						$paramsArr[] = '%' . $val . '%';
						$paramsArr[] = '%' . $val . '%';
					}
				}
				if (count($condSearchWord) != 0) {
					$cond .= ' AND (' . implode(' OR ', $condSearchWord) . ')';
				}
			}

			$fArr['datum']['attributes'] = '';
			$fArr['datum']['order'] = 0;
			$fArr['datum']['ox'] = '';
			$fArr['datum']['value'] = 'Datum';

			$fArr['zeit']['attributes'] = '';
			$fArr['zeit']['order'] = 0;
			$fArr['zeit']['ox'] = '';
			$fArr['zeit']['value'] = 'Zeit';

			$fArr['titel']['attributes'] = '';
			$fArr['titel']['order'] = 0;
			$fArr['titel']['ox'] = '';
			$fArr['titel']['value'] = 'Titel';

			$fArr['ort']['attributes'] = '';
			$fArr['ort']['order'] = 0;
			$fArr['ort']['ox'] = '';
			$fArr['ort']['value'] = 'Ort';

			$fArr['name']['attributes'] = '';
			$fArr['name']['order'] = 0;
			$fArr['name']['ox'] = '';
			$fArr['name']['value'] = 'Erfasser';

			$fArr['co']['attributes'] = '';
			$fArr['co']['order'] = 1;
			$fArr['co']['ox'] = 'ASC';
			$fArr['co']['value'] = 'Status';

			$fArr['action']['attributes'] = '';
			$fArr['action']['order'] = 0;
			$fArr['action']['ox'] = '';
			$fArr['action']['value'] = '&nbsp;';

			$pos = 0;
			$ox = "";
			$orderby = "j.datumVon DESC, j.datumBis DESC";
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
    COUNT(*) AS anz
    
  FROM
    jahresprogramm j
    
  "."{$cond}
  ";
			$qry = $this->db->query($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {
				$pagination = $this->showPage->getPagenavi("jp", $res->anz, $pos);
				$liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      j.ID, j.zeit, j.titel, j.ort, CONCAT(b.vorname, ' ', b.nachname) AS name,
      IF(j.datumVon=j.datumBis, DATE_FORMAT(j.datumVon, '%d.%m.%Y'), CONCAT(DATE_FORMAT(j.datumVon, '%d.%m.%Y'), '-', DATE_FORMAT(j.datumBis, '%d.%m.%Y'))) AS datum, IF(j.confirmed='0000-00-00 00:00:00', 'tocheck', 'active') co, j.registered_by
      
    FROM
      jahresprogramm j
      LEFT JOIN benutzer b ON j.registered_by=b.ID
    
    "."{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$qry = $this->db->query($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {

					$xs = ($res->co == 'tocheck') ? 'zu prüfen' : 'aktiv';

					$href = "anlassDet-{$res -> ID}.html";
					$liste .= "<tr class=\"{$res -> co}\"><td>{$res -> datum}</td>\n<td>{$res -> zeit}</td>\n<td><a href=\"{$href}\" class=\"edit\">{$res -> titel}</a></td>\n<td>{$res -> ort}</td>\n<td>";
					if ($this->showPage->checkUG('admin')) {
						$href = "benutzerDet-{$res -> registered_by}.html";
						$liste .= "<a href=\"{$href}\">{$res -> name}</a>";
					} else {
						$liste .= $res->name;
					}
					$href = "jp.html?remove={$res -> ID}";
					$liste .= "</td>\n<td>{$xs}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"{$href}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}

			foreach ($jpArr['typen'] AS $key => $val) {
				$typen .= "<option value=\"{$key}\"";
				if ((string)$key === $typ) {
					$typen .= ' selected="selected"';
				}
				$typen .= ">{$val['titel']}</option>\n";
			}

			foreach ($vArr AS $key => $val) {
				$vereine .= "<option value=\"{$key}\"";
				if ((string)$key === $vereinID) {
					$vereine .= ' selected="selected"';
				}
				$vereine .= ">{$val}</option>\n";
			}
		}

		$this->placeholders['stichwort'] = $stichwort;
		$this->placeholders['typen'] = $typen;
		$this->placeholders['vereine'] = $vereine;
		$this->placeholders['liste'] = $liste;
	}
}


/* EOF */