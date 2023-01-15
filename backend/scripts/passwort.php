<?php namespace backend\scripts;

use classes\pageClass;

class passwort extends pageClass
{
	public function execute()
	{
		$status = '';

		$ID = 0;
		$code = '';

		$fehlerArr = [];

		if (!$this->showPage->checkAccess()) {

			if (isset($this->showPage->arrVars[2])) {
				$ID = (int)$this->showPage->arrVars[1];
				$code = $this->showPage->arrVars[2];

				if ($code == md5("bsvpas{$ID}buelach")) {
					if (isset($_GET['send'])) {

						if (!isset($_POST['passwort1']) || $_POST['passwort1'] == '') {
							$fehlerArr[] = "Geben Sie ein Passwort ein.";
						} else if ($_POST['passwort1'] != $_POST['passwort2']) {
							$fehlerArr[] = "Sie haben nicht zweimal dasselbe Passwort eingegeben.";
						} else {
							$passwort = md5($_POST['passwort1']);
							$sql = "UPDATE benutzer SET passwort=?, wronglogin=? WHERE ID=?";
							$this->db->prepareAndExecute($sql, [$passwort, 0, $ID]);
							$this->showPage->redirect("passwortRes.html");
						}
					}
				} else {
					$fehlerArr[] = 'Das Passwort kann nicht geändert werden, da ein ungültiger Link eingegeben wurde.';
				}
			} else {
				$fehlerArr[] = "Das Passwort kann nicht geändert werden, da ein ungültiger Link eingegeben wurde.";
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= '</ul></div>';
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['code'] = $code;
	}
}