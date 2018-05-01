<?php

namespace scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class anlass extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$dokumente = '';

		if ($this->showPage->arrVars[1] == 'all') {
			$year = (isset($this->showPage->arrVars[2])) ? $this->showPage->arrVars[2] : 0;
			$month = (isset($this->showPage->arrVars[3])) ? $this->showPage->arrVars[3] : 0;

			$backlink = "jpAll-{$year}-{$month}.html";
			$this->showPage->pageArr['navistufe'][2] = "jpAll";
		} else {
			$jpArr = $bsvb->getJahresprogramm();

			$gruppe = (isset($this->showPage->arrVars[1]) && array_key_exists($this->showPage->arrVars[1], $jpArr['gruppen'])) ? $this->showPage->arrVars[1] : 'sa';

			if (count($jpArr['gruppen'][$gruppe]) == 0) {
				$typ = $gruppe;
			} else {
				$typ = (isset($this->showPage->arrVars[2]) && in_array($this->showPage->arrVars[2], $jpArr['gruppen'][$gruppe])) ? $this->showPage->arrVars[2] : current($jpArr['gruppen'][$gruppe]);

				$gruppen = "<ul>\n";
				foreach ($jpArr['gruppen'][$gruppe] AS $val) {
					$href = "jp-{$gruppe}-{$val}.html";
					$gt = ($val == $typ) ? "<strong>{$jpArr['typen'][$val]['titel']}</strong>" : "<a href=\"{$href}\">{$jpArr['typen'][$val]['titel']}</a>";
					$gruppen .= "<li>{$gt}</li>\n";
				}
			}

			$this->showPage->pageArr['navistufe'][2] = "jp{$gruppe}";

			$jahr = (isset($this->showPage->arrVars[3])) ? $this->showPage->arrVars[3] : date("Y");

			$backlink = "jp-{$gruppe}-{$typ}-{$jahr}.html";
		}
		$ID = (isset($this->showPage->arrVars[4])) ? $this->showPage->arrVars[4] : 0;

		$sql = "
SELECT
  v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.zeit, p.bemerkungen, p.export, p.vorstand
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.ID=?
";
		$qry = $this->db->query($sql, [$ID]);
		if ($qry->rowCount() == 0) {
			$this->showPage->redirect("jp.html");
		}
		$res = $qry->fetchObject();

		$this->showPage->pageArr['platzhalter']['title'] = $res->titel;
		$verein = ($res->verein == '') ? 'unbekannt' : $res->verein;
		$datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res -> datumVon} - {$res -> datumBis}";
		$zeit = $res->zeit;
		$ort = $res->ort;
		$bemerkungen = ($res->bemerkungen == '' || $res->vorstand == 1) ? '' : "<dl class=\"group\"><dt>Bemerkungen:</dt><dd>" . nl2br($res->bemerkungen) . "</dd></dl>";
		$href = "/calendar/{$ID}/event.ics";
		$export = ($res->export == 0) ? '' : "<dl class=\"group\"><dt>Kalenderexport:</dt><dd><a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a></dd></dl>";

		$pdfArr = [];

		if ($res->vorstand != 1) {
			$sql = "
		SELECT
  			d.ID, d.dateiname, d.titel, f.extension
        
		FROM
  			dokumente d
  			INNER JOIN dateiformate f ON d.type=f.mimetype
        
		WHERE
  			d.objekt='anlass' AND d.objektID=?
      
		ORDER BY
  			titel
  		";
			$qry = $this->db->query($sql, [$ID]);
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $res['ID'] . '.' . $res['extension'])) {
					$key = md5("aasmdsjtk{$res['ID']}asujdt3?nz34g");

					$doktitel = ($res['titel'] == '') ? 'ohne Titel' : $res['titel'];
					$pdfArr[] = "<li><a href=\"/dokumente/{$res['ID']}/{$key}/" . urlencode($res['dateiname']) . "\">{$doktitel}</a></li>\n";
				}
			}

			if (count($pdfArr) != 0) {
				$dokumente = "<dl class=\"group\"><dt>Dokument(e):</dt><dd><ul class=\"pdflink\">\n" . implode("\n", $pdfArr) . "</ul></dd></dl>";
			}
		}

		$this->placeholders['backlink'] = $backlink;
		$this->placeholders['verein'] = $verein;
		$this->placeholders['datum'] = $datum;
		$this->placeholders['zeit'] = $zeit;
		$this->placeholders['ort'] = $ort;
		$this->placeholders['bemerkungen'] = $bemerkungen;
		$this->placeholders['dokumente'] = $dokumente;
		$this->placeholders['export'] = $export;
	}
}

/* EOF */