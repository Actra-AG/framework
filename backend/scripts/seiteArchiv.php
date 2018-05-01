<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class seiteArchiv extends pageClass
{
	public function execute() {
		$status = '';
		$x = '';
		$liste = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor') && isset($this->showPage->arrVars[2])) {
			$oArr['frontend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/pages/';
			$oArr['frontend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/frontend/config/';
			$oArr['frontend']['name'] = "Frontend";

			$oArr['backend']['pages'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/pages/';
			$oArr['backend']['config'] = $_SERVER['DOCUMENT_ROOT'] . '/backend/config/';
			$oArr['backend']['name'] = "Backend";

			$ort = 'frontend';
			if (isset($this->showPage->arrVars[1]) && isset($oArr[$this->showPage->arrVars[1]])) {
				$ort = $this->showPage->arrVars[1];
			}

			$path_pages = $oArr[$ort]['pages'];
			$path_config = $oArr[$ort]['config'];

			$seite = $this->showPage->arrVars[2];

			if (!file_exists($path_pages . "{$seite}.html") || !file_exists($path_config . "{$seite}.php")) {
				$this->showPage->redirect("seiten.html");
			}

			$x = "{$seite} ({$ort})";

			$paramsArr[] = $ort;
			$paramsArr[] = $seite;

			$sql = "SELECT s.ID, DATE_FORMAT(s.datum, '%d.%m.%Y %T') AS datum, CONCAT(b.vorname, ' ', b.nachname) AS name FROM seiteninhalte s LEFT JOIN benutzer b ON s.benutzerID=b.ID WHERE s.ort=? AND s.seite=? ORDER BY s.datum DESC";
			$qry = $this->db->query($sql, $paramsArr);
			if ($qry->rowCount() == 0) {
				$liste = "<p>Von dieser Seite gibt es keine archivierte Versionen.</p>";
			} else {
				$liste = "<ul>\n";
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
					$href = "archiv-seite-{$res['ID']}.html";
					$liste .= "<li><a href=\"{$href}\">{$res['datum']}</a> [{$res['name']}]</li>";
				}

				$liste .= "</ul>";
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
		$this->placeholders['x'] = $x;
		$this->placeholders['liste'] = $liste;
	}
}


/* EOF */