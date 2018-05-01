<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class newsMod extends pageClass
{
	public function execute() {
		$bsvb = new bsvb();

		$status = '';
		$ID = 0;
		$archiv0 = '';
		$archiv1 = '';

		$datenArr['datum'] = date("d.m.Y");
		$datenArr['titel'] = '';
		$datenArr['teaser'] = '';
		$datenArr['text'] = '';
		$datenArr['archiv'] = 0;

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "
	SELECT
	  archiv, titel, teaser, text, IF(datum='0000-00-00', '', DATE_FORMAT(datum, '%d.%m.%Y')) AS datum
	  
	FROM
	  news
	  
	WHERE
	  ID=?
	";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Neuigkeit bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
			} else {
				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Neuigkeit hinzufügen';
			}

			if (isset($_GET['send'])) {

				if (!isset($_POST['datum']) || $_POST['datum'] == '') {
					$fehlerArr[] = 'Sie haben kein Datum eingegeben.';
				} else if (!preg_match("/[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}/", $_POST['datum'])) {
					$fehlerArr[] = 'Sie haben ein ungültiges Datum eingegeben.';
				} else {
					$datenArr['datum'] = $_POST['datum'];
				}

				if (!isset($_POST['titel']) || $_POST['titel'] == '') {
					$fehlerArr[] = 'Sie haben keinen Titel eingegeben.';
				} else {
					$datenArr['titel'] = $_POST['titel'];
				}

				if (isset($_POST['teaser'])) {
					$datenArr['teaser'] = $_POST['teaser'];
				}
				if (isset($_POST['text'])) {
					$datenArr['text'] = $_POST['text'];
				}

				if (!isset($_POST['archiv']) || ($_POST['archiv'] != 0 && $_POST['archiv'] != 1)) {
					$fehlerArr[] = 'Sie haben nichts bei Archiv ausgewählt.';
				} else {
					$datenArr['archiv'] = $_POST['archiv'];
				}

				if (count($fehlerArr) == 0) {

					$datenArr['ID'] = $ID;
					if ($datenArr['datum'] != '') {
						$xArr = explode(".", $datenArr['datum']);
						$datenArr['datum'] = "{$xArr[2]}-{$xArr[1]}-{$xArr[0]}";
					}

					if ($ID == 0) {
						$datenArr['registered_by'] = $this->showPage->userData->ID;
						$datenArr['typ'] = 1;
						$ID = $bsvb->insertEntry('news', $datenArr);
					} else {
						$bsvb->updateEntry('news', $ID, $datenArr);
					}

					$this->showPage->redirect("news.html?ac={$ac}");
				}
			}

			if ($datenArr['archiv'] == 0) {
				$archiv0 = ' checked="checked"';
			}
			if ($datenArr['archiv'] == 1) {
				$archiv1 = ' checked="checked"';
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
		$this->placeholders['ID'] = $ID;
		$this->placeholders['archiv0'] = $archiv0;
		$this->placeholders['archiv1'] = $archiv1;

		foreach ($datenArr AS $key => $val) {
			$this->placeholders[$key] = $val;
		}
	}
}


/* EOF */