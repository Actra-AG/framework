<?php

namespace scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class jp extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$gruppen = '';
		$liste = '';

		$jpArr = $bsvb->getJahresprogramm();

		$gruppe = (isset($this->showPage->arrVars[1]) && array_key_exists($this->showPage->arrVars[1], $jpArr['gruppen']) && $this->showPage->arrVars[1] != 'vorstand') ? $this->showPage->arrVars[1] : '';
		if (!isset($jpArr['gruppen'][$gruppe])) {
			$this->showPage->redirect("jpAll.html");
		}

		if (count($jpArr['gruppen'][$gruppe]) == 0) {
			$typ = $gruppe;
		} else {
			$typ = (isset($this->showPage->arrVars[2]) && in_array($this->showPage->arrVars[2], $jpArr['gruppen'][$gruppe])) ? $this->showPage->arrVars[2] : current($jpArr['gruppen'][$gruppe]);

			$gruppen = "<div id=\"nav-content\" class=\"group\"><ul>\n";
			foreach ($jpArr['gruppen'][$gruppe] as $val) {
				$href = "jp-{$gruppe}-{$val}.html";
				$gt = ($val == $typ) ? "<strong>{$jpArr['typen'][$val]['kurz']}</strong>" : "<a href=\"{$href}\">{$jpArr['typen'][$val]['kurz']}</a>";
				$gruppen .= "<li>{$gt}</li>\n";
			}
			$gruppen .= "</ul>\n</div>";
		}
		$this->showPage->pageArr['platzhalter']['title'] = $jpArr['typen'][$typ]['titel'];
		$this->showPage->pageArr['navistufe'][2] = "jp{$gruppe}";

		$intro = (isset($jpArr['typen'][$typ]['intro'])) ? $jpArr['typen'][$typ]['intro'] : '';
		$cond = " WHERE p.{$typ}=1 AND p.confirmed!='0000-00-00 00:00:00'";
		$addCond = (count($jpArr['typen'][$typ]['conditions']) == 0) ? "" : " AND " . implode(" AND ", $jpArr['typen'][$typ]['conditions']);

		$qry = $this->db->prepareAndExecute(sql: "SELECT DATE_FORMAT(MAX(p.lastmod), '%d.%m.%Y %T') AS lastmod FROM jahresprogramm p" . $cond);
		$res = $qry->fetch(PDO::FETCH_ASSOC);
		$lastmod = ($res['lastmod'] != '') ? $res['lastmod'] : 'unbekannt';

		$qry = $this->db->prepareAndExecute(sql: "SELECT MIN(YEAR(p.datumVon)) AS minJahr, MAX(YEAR(p.datumBis)) AS maxJahr FROM jahresprogramm p " . $cond . $addCond);
		$res = $qry->fetch(PDO::FETCH_ASSOC);
		$minJahr = ($res['minJahr'] != '') ? $res['minJahr'] : date("Y");
		$maxJahr = ($res['maxJahr'] != '') ? $res['maxJahr'] : date("Y");
		$currJahr = (isset($this->showPage->arrVars[3]) && is_numeric($this->showPage->arrVars[3]) && $this->showPage->arrVars[3] >= $minJahr && $this->showPage->arrVars[3] <= $maxJahr) ? $this->showPage->arrVars[3] : date("Y");
		if ($currJahr < $minJahr || $currJahr > $maxJahr) {
			$currJahr = $minJahr;
		}

		$jArr = [];
		for ($i = $maxJahr; $i >= $minJahr; $i--) {
			if ($i == $currJahr) {
				$jArr[] = "<strong>{$i}</strong>";
			} else {
				$href = "jp-{$gruppe}-{$typ}-{$i}.html";
				$jArr[] = "<a href=\"{$href}\">{$i}</a>";
			}
		}
		$jahresnavi = "<ul id=\"nav-year\" class=\"group\"><li>" . implode("</li><li>", $jArr) . "</li></ul>";

		$cond = "WHERE {$typ}=1 AND confirmed!='0000-00-00 00:00:00' AND YEAR(p.datumVon)<=? AND YEAR(p.datumBis)>=?{$addCond}";
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

		$fArr['p.export']['attributes'] = '';
		$fArr['p.export']['order'] = 0;
		$fArr['p.export']['ox'] = '';
		$fArr['p.export']['value'] = '&nbsp;';

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

		$sql = "SELECT COUNT(p.ID) AS anz FROM jahresprogramm p";
		$sql .= " " . $cond;
		$qry = $this->db->prepareAndExecute($sql, $paramsArr);
		$res = $qry->fetchObject();
		if ($res->anz == 0) {
			$liste = "<p class=\"no-entry\">Es sind keine Anlässe erfasst.</p>";
		} else {
			$liste .= "<table cellspacing=\"0\" class=\"normtable\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

			$i = 0;
			$sql = "
  SELECT
    ID, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.export
    
      
  FROM
    jahresprogramm p
  ";
			$sql .= " " . $cond;
			$sql .= " ORDER BY $orderby $ox";

			$qry = $this->db->prepareAndExecute($sql, $paramsArr);
			while ($res = $qry->fetchObject()) {
				$i++;
				$alt = ($i % 2 == 0) ? ' class="alt"' : '';
				$datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res->datumVon} - <br />{$res->datumBis}";
				$filename = 'event.ics';
				$href = "/calendar/{$res->ID}/{$filename}";
				$export = ($res->export == 1) ? "<a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a>" : '';

				$href = "anlass-{$gruppe}-{$typ}-{$currJahr}-{$res->ID}.html";
				$liste .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"{$href}\">{$res->titel}</a></td>\n<td>{$res->ort}</td>\n<td>{$export}</td></tr>\n";
			}
			$liste .= "</tbody>\n</table>";
		}

		$this->placeholders['lastmod'] = $lastmod;
		$this->placeholders['intro'] = $intro;
		$this->placeholders['gruppen'] = $gruppen;
		$this->placeholders['jahresnavi'] = $jahresnavi;
		$this->placeholders['liste'] = $liste;
	}
}

/* EOF */