<?php namespace backend\scripts;

use classes\pageClass;

class seiteMod extends pageClass
{
	public function execute()
	{
		$status = '';
		$ort = '';
		$seite = '';

		$datenArr['text'] = '';
		$datenArr['config'] = '';

		if ($this->showPage->checkUG('redaktor') && isset($this->showPage->arrVars[2])) {
			$benutzerID = (int)$this->showPage->userData->ID;

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

			$datenArr['text'] = file_get_contents($path_pages . $seite . '.html');
			$datenArr['config'] = file_get_contents($path_config . $seite . '.php');

			if (isset($_GET['send'])) {
				if (isset($_POST['text'])) {
					$f = fopen($path_pages . "{$seite}.html", "w");
					fwrite($f, stripslashes($_POST['text']));
					fclose($f);
				}

				if (isset($_POST['config'])) {
					$f = fopen($path_config . "{$seite}.php", "w");
					fwrite($f, stripslashes($_POST['config']));
					fclose($f);
				}

				$paramsArr[] = $benutzerID;
				$paramsArr[] = $ort;
				$paramsArr[] = $seite;
				$paramsArr[] = $_POST['text'];
				$paramsArr[] = $_POST['config'];

				$this->db->prepareAndExecute("INSERT INTO seiteninhalte SET benutzerID=?, ort=?, seite=?, inhalt=?, config=?", $paramsArr);
			}

			$datenArr['text'] = htmlentities($datenArr['text'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
			$datenArr['config'] = htmlentities($datenArr['config'], ENT_COMPAT | ENT_HTML401, 'UTF-8');
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['ort'] = $ort;
		$this->placeholders['seite'] = $seite;

		foreach ($datenArr as $key => $val) {
			$this->placeholders[$key] = $val;
		}
	}
}