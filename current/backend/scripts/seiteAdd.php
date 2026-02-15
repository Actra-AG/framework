<?php namespace backend\scripts;

use classes\pageClass;

class seiteAdd extends pageClass
{
	public function execute()
	{
		$status = '';
		$ort = '';
		$ortname = '';
		$seite = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {
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
			$ortname = $oArr[$ort]['name'];

			if (isset($_GET['send'])) {

				if (!isset($_POST['seite']) || $_POST['seite'] == '') {
					$fehlerArr[] = 'Sie haben keine Adresse eingegeben.';
				} else {
					$seite = $_POST['seite'];
					if (!preg_match("/^[a-zA-Z0-9]+$/", $_POST['seite'])) {
						$fehlerArr[] = 'Die Adresse darf keine Sonder- und Leerzeichen beinhalten.';
					} else if (file_exists($path_pages . "{$seite}.html") || file_exists($path_config . "{$seite}.php")) {
						$fehlerArr[] = 'Diese Adresse existiert bereits.';
					}
				}

				if (count($fehlerArr) == 0) {
					$f = fopen($path_pages . "{$seite}.html", "w");
					fclose($f);
					chmod($path_pages . "{$seite}.html", 0777);

					$f = fopen($path_config . "{$seite}.php", "w");
					fwrite($f, '<?php namespace backend\scripts;  use classes\pageClass;  class login extends pageClass { 	public function execute() 	{ 	} }' . "\n");
					fwrite($f, '//Grundkonfiguration' . "\n");
					fwrite($f, '$grundkonf[\'templateID\'] = \'1\';' . "\n\n");
					fwrite($f, '//Platzhalter' . "\n");
					fwrite($f, '$this->placeholders[\'title\'] = \'SEITENTITEL\';' . "\n");
					fwrite($f, '$this->placeholders[\'description\'] = \'description\';' . "\n\n");
					fwrite($f, '// Zusatzcode in Header' . "\n");
					fwrite($f, '$this->placeholders[\'scripts\'] = \'\';' . "\n\n");
					fwrite($f, '// Navigation' . "\n");
					fwrite($f, '$navistufe[1] = \'\';' . "\n");
					fwrite($f, '/* EOF */');
					fclose($f);
					chmod($path_config . "{$seite}.php", 0777);

					$this->showPage->redirect("seiteMod-{$ort}-{$seite}.html");
				}
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
		$this->placeholders['ort'] = $ort;
		$this->placeholders['ortname'] = $ortname;
		$this->placeholders['seite'] = $seite;
	}
}