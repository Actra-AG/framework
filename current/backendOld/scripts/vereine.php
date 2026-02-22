<?php namespace backend\scripts;

use classes\pageClass;

class vereine extends pageClass
{
	public function execute()
	{
		$stichwort = '';
		$liste = '';

		$fn = 'adm_vereine';

		if ($this->showPage->checkUG('admin')) {

			if (isset($_GET['remove'])) {
				$this->db->prepareAndExecute("DELETE FROM vereine WHERE ID=?", [$_GET['remove']]);
			}

			$cond = "WHERE 1=1";
			$paramsArr = [];

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
						$condSearchWord[] = "(v.name LIKE ?)";
						$paramsArr[] = '%' . $val . '%';
					}
				}
				if (count($condSearchWord) != 0) {
					$cond .= ' AND (' . implode(' OR ', $condSearchWord) . ')';
				}
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
    vereine v
    
  " . "{$cond}
  ";
			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {
				$pagination = $this->showPage->getPagenavi("vereine", $res->anz, $pos);
				$liste = "<p class=\"search-result\">Es wurde(n) <strong>{$res->anz}</strong> Resultat(e) gefunden.</p>\n";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      v.ID, v.name
      
    FROM
      vereine v
    
    " . "{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$qry = $this->db->prepareAndExecute($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {
					$href1 = "vereinMod-{$res -> ID}.html";
					$href2 = "vereine.html?remove={$res -> ID}";
					$liste .= "<tr><td>{$res -> ID}</td>\n<td>{$res -> name}</td>\n<td class=\"aktion\"><ul>\n<li><a href=\"{$href1}\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"{$href2}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}
		}

		$this->placeholders['stichwort'] = $stichwort;
		$this->placeholders['liste'] = $liste;
	}
}