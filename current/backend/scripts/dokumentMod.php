<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;

class dokumentMod extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$status = '';
		$objekt = '';
		$objektID = '';
		$ID = '';
		$dokument = '';

		$datenArr['titel'] = '';

		$fehlerArr = [];
		$next = '';

		if ($this->showPage->checkUG('aktiv')) {

			$myID = $this->showPage->userData->ID;
			$objekt = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 'anlass';
			$objektID = (isset($this->showPage->arrVars[2])) ? $this->showPage->arrVars[2] : 0;
			$ID = (isset($this->showPage->arrVars[3])) ? $this->showPage->arrVars[3] : 0;

			if ($objekt == 'anlass') {
				$sql = "
	  SELECT
  	  titel
	  
  	FROM
	    jahresprogramm
	  
	  WHERE
  	  ID=? AND (registered_by=? OR {$this->showPage->userData->admin}=1)
  	";
				$qry = $this->db->prepareAndExecute($sql, [$objektID, $myID]);
				if ($qry->rowCount() == 0) {
					$this->showPage->redirect("jp.html");
				}
				$next = "anlassDet-{$objektID}";
			} else {
				$this->showPage->redirect("start.html");
			}

			$sql = "SELECT titel FROM dokumente WHERE objekt=? AND objektID=? AND ID=?";
			$qry = $this->db->prepareAndExecute($sql, [$objekt, $objektID, $ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Dokument bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
			} else {
				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Dokument hinzufügen';
			}

			if (isset($_GET['send'])) {

				$extension = '';
				if (isset($_FILES['doc']) && $_FILES['doc']['name'] != '') {
					$mimetype = $_FILES['doc']['type'];
					$sql = "SELECT extension FROM dateiformate WHERE mimetype=? AND FIND_IN_SET('dokumente', arten)!=0";
					$qry = $this->db->prepareAndExecute($sql, [$mimetype]);
					if ($qry->rowCount() == 1) {
						$res = $qry->fetch(PDO::FETCH_ASSOC);
						$extension = $res['extension'];
						$datenArr['type'] = $mimetype;

						if (preg_match("/^([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9]{2,3})$/", $_FILES['doc']['name'])) {
							$datenArr['dateiname'] = $_FILES['doc']['name'];
						} else {
							$fehlerArr[] = 'Die Datei hat einen ungültigen Dateinamen. Dieser darf keine Sonder- und Leerzeichen beinhalten!';
						}
					} else {
						$fehlerArr[] = 'Die Datei hat ein ungültiges Dateiformat: ' . $mimetype;
					}
				} else if ($ac == 'add') {
					$fehlerArr[] = 'Sie haben keine Datei ausgewählt.';
				}

				if (isset($_POST['titel'])) {
					$datenArr['titel'] = $_POST['titel'];
				}

				if (count($fehlerArr) == 0) {

					if ($ID == 0) {
						$datenArr['objekt'] = $objekt;
						$datenArr['objektID'] = $objektID;

						$ID = $bsvb->insertEntry('dokumente', $datenArr);

						move_uploaded_file($_FILES['doc']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $ID . '.' . $extension);
						chmod($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $ID . '.' . $extension, 0777);
					} else {
						$bsvb->updateEntry('dokumente', $ID, $datenArr);
					}

					$this->showPage->redirect("{$next}.html?{$ac}");
				}
			}

			if ($ac == 'add') {
				$doctitel = 'Dokument';
				$dokument .= "<dl><dt><label for=\"doc\">{$doctitel}</label></dt><dd><input type=\"file\" name=\"doc\" id=\"doc\" /></dd>\n";
				if ($ID != 0) {
					$dokument .= "<!--<input type=\"submit\" class=\"submit\" name=\"docAdd\" value=\"hochladen\" />-->";
				}
				$dokument .= "</dl>";
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['objekt'] = $objekt;
		$this->placeholders['objektID'] = $objektID;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['dokument'] = $dokument;

		foreach ($datenArr as $key => $val) {
			$this->placeholders[$key] = $val;
		}
	}
}