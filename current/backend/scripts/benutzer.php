<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class benutzer extends pageClass
{
	public function execute()
	{
		$vereine = '';
		$stichwort = '';
		$liste = '';

		$fn = 'adm_benutzer';

		if ($this->showPage->checkUG('admin')) {

			$vArr = [];

			$vArr[0] = 'keine Einschränkungen';
			$sql = "SELECT ID, name FROM vereine ORDER BY name";
			$qry = $this->db->prepareAndExecute($sql);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				$vArr[$res['ID']] = $res['name'];
			}

			if (isset($_GET['remove'])) {
				$this->db->prepareAndExecute("DELETE FROM benutzer WHERE ID=?", [$_GET['remove']]);
			}

			$cond = "WHERE 1=1";
			$paramsArr = [];

			if (isset($_POST['vereinID']) && isset($vArr[$_POST['vereinID']])) {
				$_SESSION[$fn]['vereinID'] = $_POST['vereinID'];
			}
			if (!isset($_SESSION[$fn]['vereinID'])) {
				$_SESSION[$fn]['vereinID'] = 0;
			}
			$vereinID = $_SESSION[$fn]['vereinID'];
			if ($vereinID != 0) {
				$cond .= " AND b.ID IN (SELECT benutzerID FROM benutzervereine WHERE vereinID=?)";
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
				foreach ($sArr as $val) {
					$sx = trim($val);
					if ($sx != '') {
						$condSearchWord[] = "(b.vorname LIKE ? OR b.nachname LIKE ? OR b.email LIKE ?)";
						$paramsArr[] = '%' . $val . '%';
						$paramsArr[] = '%' . $val . '%';
						$paramsArr[] = '%' . $val . '%';
					}
				}
				if (count($condSearchWord) != 0) {
					$cond .= ' AND (' . implode(' OR ', $condSearchWord) . ')';
				}
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
    benutzer b
    
  ";
			$sql .= " {$cond}";
			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {
				$pagination = $this->showPage->getPagenavi("benutzer", $res->anz, $pos);
				$liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      b.ID, b.vorname, b.nachname, b.email, IF(b.aktiv=1, 'ja', 'nein') AS aktiv, IF(b.admin=1, 'ja', 'nein') AS admin, IF(b.ehren=1, 'ja', 'nein') AS ehren, IF(b.vorstand=1, 'ja', 'nein') AS vorstand, IF(b.confirmed='0000-00-00 00:00:00', 'Aktivierung ausstehend', IF(b.accepted!='0000-00-00 00:00:00', 'akzeptiert', IF(b.denied!='0000-00-00 00:00:00', 'abgelehnt', 'zu prüfen'))) AS co
      
    FROM
      benutzer b
    
    " . "{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$qry = $this->db->prepareAndExecute($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {
					$href1 = "benutzerDet-{$res -> ID}.html";
					$href2 = "benutzer.html?remove={$res -> ID}";
					$liste .= "<tr><td>{$res -> vorname}</td>\n<td>{$res -> nachname}</td>\n<td>{$res -> email}</td>\n<td>{$res -> aktiv}</td>\n<td>{$res -> admin}</td>\n<td>{$res -> ehren}</td>\n<td>{$res -> vorstand}</td>\n<td>{$res -> co}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"{$href1}\" class=\"edit\">Details</a></li>\n<li><a href=\"{$href2}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}

			foreach ($vArr as $key => $val) {
				$vereine .= "<option value=\"{$key}\"";
				if ($key == $vereinID) {
					$vereine .= ' selected="selected"';
				}
				$vereine .= ">{$val}</option>\n";
			}
		}

		$this->placeholders['vereine'] = $vereine;
		$this->placeholders['stichwort'] = $stichwort;
		$this->placeholders['liste'] = $liste;
	}
}
/* EOF */