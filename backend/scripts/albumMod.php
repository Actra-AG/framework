<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class albumMod extends pageClass
{
	public function execute() {
		$bsvb = new bsvb();

		$status = '';
		$ID = '';

		$datenArr['titel'] = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "SELECT titel FROM alben WHERE ID=?";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Album bearbeiten';
				$this->showPage->pageArr['grundkonf']['navigator']['title'] = 'Album bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
			} else {

				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Album hinzufügen';
				$this->showPage->pageArr['grundkonf']['navigator']['title'] = 'Album hinzufügen';
			}

			if (isset($_GET['send'])) {

				if (!isset($_POST['titel']) || $_POST['titel'] == '') {
					$fehlerArr[] = 'Sie haben keinen Titel eingegeben.';
				} else {
					$datenArr['titel'] = $_POST['titel'];
				}

				if (count($fehlerArr) == 0) {

					if ($ID == 0) {
						$sql = "SELECT MAX(pos)+1 AS pos FROM alben";
						$qry = $this->db->query($sql);
						$res = $qry->fetch(PDO::FETCH_ASSOC);
						$datenArr['pos'] = $res['pos'];
						$datenArr['typ'] = 1;

						$ID = $bsvb->insertEntry('alben', $datenArr);
					} else {
						$bsvb->updateEntry('alben', $ID, $datenArr);
					}

					$this->showPage->redirect("alben.html?{$ac}");
				}
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

		foreach ($datenArr AS $key => $val) {
			$this->placeholders[$key] = htmlentities($val);
		}
	}
}


/* EOF */