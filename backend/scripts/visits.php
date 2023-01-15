<?php namespace backend\scripts;

use classes\pageClass;

class visits extends pageClass
{
	public function execute()
	{
		$stichwort = '';
		$liste = '';

		$fn = 'adm_visits';

		if ($this->showPage->checkUG('admin')) {

			if (isset($_REQUEST['stichwort'])) {
				$_SESSION[$fn]['stichwort'] = $_REQUEST['stichwort'];
				$_SESSION[$fn]['pos'] = 0;
			}

			if (isset($_SESSION[$fn]['stichwort'])) {
				$stichwort = $_SESSION[$fn]['stichwort'];
			}

			$paramsArr = [];
			$cond = "WHERE 1=1";

			if (strlen(trim($stichwort)) != 0) {
				if (preg_match("/^(benutzerID|ip|sessionID)::([0-9a-zA-Z\.]*)/", $stichwort, $treffer)) {
					$cond .= " AND v.{$treffer[1]}=?";
					$paramsArr[] = $treffer[2];
				} else {
					$condSearchWord = [];
					$sArr = explode(" ", $stichwort);
					foreach ($sArr as $val) {
						$sx = trim($val);
						if ($sx != '') {
							$condSearchWord[] = "(u.vorname LIKE ? OR u.nachname LIKE ? OR v.sessionID LIKE ? OR v.ip LIKE ?)";
							$paramsArr[] = '%' . $val . '%';
							$paramsArr[] = '%' . $val . '%';
							$paramsArr[] = '%' . $val . '%';
							$paramsArr[] = '%' . $val . '%';
						}
					}
					if (count($condSearchWord) != 0) {
						$cond .= ' AND (' . implode(' OR ', $condSearchWord) . ')';
					}
				}
			}

			$fArr['v.datum']['attributes'] = '';
			$fArr['v.datum']['order'] = 1;
			$fArr['v.datum']['ox'] = 'DESC';
			$fArr['v.datum']['value'] = 'Datum';

			$fArr['name']['attributes'] = '';
			$fArr['name']['order'] = 1;
			$fArr['name']['ox'] = 'ASC';
			$fArr['name']['value'] = 'Name';

			$fArr['v.sessionID']['attributes'] = '';
			$fArr['v.sessionID']['order'] = 1;
			$fArr['v.sessionID']['ox'] = 'ASC';
			$fArr['v.sessionID']['value'] = 'SessionID';

			$fArr['v.ip']['attributes'] = '';
			$fArr['v.ip']['order'] = 1;
			$fArr['v.ip']['ox'] = 'ASC';
			$fArr['v.ip']['value'] = 'IP-Adresse';

			$pos = 0;
			$ox = "DESC";
			$orderby = "v.datum";
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
    visits v
    LEFT JOIN benutzer u ON v.benutzerID=u.ID
  
  " . "{$cond}
  
  ";
			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {
				$pagination = $this->showPage->getPagenavi("visits", $res->anz, $pos);
				$liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      DATE_FORMAT(v.datum, '%d.%m.%Y %T') AS datum, CONCAT(u.vorname, ' ', u.nachname) AS name, v.benutzerID, v.sessionID, v.ip
      
    FROM
      visits v
      LEFT JOIN benutzer u ON v.benutzerID=u.ID
      
    " . "{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$qry = $this->db->prepareAndExecute($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {
					$href1 = "benutzerDet-{$res -> benutzerID}.html";
					$href2 = "visits.html?stichwort=sessionID::{$res -> sessionID}";
					$href3 = "visits.html?stichwort=ip::{$res -> ip}";
					$liste .= "<tr><td>{$res -> datum}</td>\n<td><a href=\"{$href1}\">{$res -> name}</a></td>\n<td><a href=\"{$href2}\">{$res -> sessionID}</a></td>\n<td><a href=\"{$href3}\">{$res -> ip}</a></td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}
		}

		$this->placeholders['stichwort'] = $stichwort;
		$this->placeholders['liste'] = $liste;
	}
}