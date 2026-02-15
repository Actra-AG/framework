<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class vorstand extends pageClass
{
	public function execute() {
		$bsvb = new bsvb();

		$jahresnavi = '';
		$liste = '';

		$jpArr = $bsvb->getJahresprogramm();

		if ($this->showPage->checkUG('vorstand')) {

			$typ = 'vorstand';
			$addCond = (count($jpArr['typen'][$typ]['conditions']) == 0) ? "" : " AND " . implode(" AND ", $jpArr['typen'][$typ]['conditions']);

			$sql = "SELECT MIN(YEAR(p.datumVon)) AS minJahr, MAX(YEAR(p.datumBis)) AS maxJahr FROM jahresprogramm p WHERE p.{$typ}=1 AND p.confirmed!='0000-00-00 00:00:00'{$addCond}";
			$qry = $this->db->prepareAndExecute($sql);
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			$minJahr = ($res['minJahr'] != '') ? $res['minJahr'] : date("Y");
			$maxJahr = ($res['maxJahr'] != '') ? $res['maxJahr'] : date("Y");
			$currJahr = (isset($this->showPage->arrVars[1]) && is_numeric($this->showPage->arrVars[1]) && $this->showPage->arrVars[1] >= $minJahr && $this->showPage->arrVars[1] <= $maxJahr) ? $this->showPage->arrVars[1] : date("Y");
			if ($currJahr < $minJahr || $currJahr > $maxJahr) {
				$currJahr = $minJahr;
			}

			$jArr = [];
			for ($i = $maxJahr; $i >= $minJahr; $i--) {
				if ($i == $currJahr) {
					$jArr[] = "<strong>{$i}</strong>";
				} else {
					$href = "vorstand-{$i}.html";
					$jArr[] = "<a href=\"{$href}\">{$i}</a>";
				}
			}
			$jahresnavi = "<p>" . implode(" | ", $jArr) . "</p>";

			$cond = "WHERE p.{$typ}=1 AND p.confirmed!='0000-00-00 00:00:00' AND YEAR(p.datumVon)<=? AND YEAR(p.datumBis)>=?{$addCond}";
			$paramsArr[] = $currJahr;
			$paramsArr[] = $currJahr;

			$fArr['p.datum']['attributes'] = '';
			$fArr['p.datum']['order'] = 0;
			$fArr['p.datum']['ox'] = '';
			$fArr['p.datum']['value'] = 'Datum';

			$fArr['p.titel']['attributes'] = '';
			$fArr['p.titel']['order'] = 0;
			$fArr['p.titel']['ox'] = '';
			$fArr['p.titel']['value'] = 'Titel';

			$fArr['p.ort']['attributes'] = '';
			$fArr['p.ort']['order'] = 0;
			$fArr['p.ort']['ox'] = '';
			$fArr['p.ort']['value'] = 'Ort';

			$fn = "jahresprogramm{$typ}";

			$ox = "";
			$orderby = "p.datumVon, p.datumBis, p.zeit";
			if (isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) {
				$_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']);
			}
			if (isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) {
				$_SESSION[$fn]['ox'] = $_GET['ox'];
			}
			if (isset($_SESSION[$fn]['orderby'])) {
				$orderby = $_SESSION[$fn]['orderby'];
			}
			if (isset($_SESSION[$fn]['ox'])) {
				$ox = $_SESSION[$fn]['ox'];
			}

			$sql = "
  SELECT
    COUNT(p.ID) AS anz
  
  FROM
    jahresprogramm p
  
  "."{$cond}
  ";
			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			$res = $qry->fetchObject();
			if ($res->anz == 0) {
				$liste = "<p class=\"no-entry\">Es sind keine Anlässe erfasst.</p>";
			} else {
				$liste .= "<table cellspacing=\"0\" summary=\"\" class=\"mitglieder\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$i = 0;
				$sql = "
    SELECT
      ID, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort
    
    FROM
      jahresprogramm p
      
    "."{$cond}
    
    ORDER BY
      {$orderby} {$ox}
    ";

				$qry = $this->db->prepareAndExecute($sql, $paramsArr);
				while ($res = $qry->fetchObject()) {
					$i++;
					$alt = ($i % 2 == 0) ? ' class="alt"' : '';
					$datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res -> datumVon} - <br />{$res -> datumBis}";

					$href = "anlassDet-{$res -> ID}.html";
					$liste .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"{$href}\">{$res -> titel}</a></td>\n<td>{$res -> ort}</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table>";
			}
		}

		$this->placeholders['jahresnavi'] = $jahresnavi;
		$this->placeholders['liste'] = $liste;
	}
}


/* EOF */