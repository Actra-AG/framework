<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class vereinMod extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$status = '';
		$ID = 0;

		$datenArr['name'] = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('admin')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "
	SELECT
	  name
	  
	FROM
	  vereine
	  
	WHERE
	  ID=?
	";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Verein bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
			} else {
				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Verein hinzufügen';
			}

			$optArr[0] = 'Nein';
			$optArr[1] = 'Ja';

			if (isset($_GET['send'])) {

				if (!isset($_POST['name']) || $_POST['name'] == '') {
					$fehlerArr[] = 'Sie haben keinen Vereinsnamen eingegeben.';
				} else {
					$datenArr['name'] = $_POST['name'];
				}

				if (count($fehlerArr) == 0) {

					$datenArr['ID'] = $ID;

					if ($ID == 0) {
						$datenArr['registered_by'] = $this->showPage->userData->ID;
						$ID = $bsvb->insertEntry('vereine', $datenArr);
					} else {
						$bsvb->updateEntry('vereine', $ID, $datenArr);
					}

					$this->showPage->redirect("vereine.html?ac={$ac}");
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
			$this->placeholders[$key] = $val;
		}
	}
}


/* EOF */