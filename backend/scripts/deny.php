<?php namespace backend\scripts;

use classes\pageClass;
use PDO;
use classes\FormMailer;

class deny extends pageClass
{
	public function execute()
	{
		$status = '';
		$mitteilung = '';
		$ID = 0;

		$fehlerArr = [];

		if ($this->showPage->checkUG('admin')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;
			$sql = "SELECT anrede, nachname, email FROM benutzer WHERE ID=?";
			$qry = $this->db->prepareAndExecute($sql, [$ID]);
			if ($qry->rowCount() == 0) {
				$this->showPage->redirect("benutzer.html");
			}
			$res = $qry->fetch(PDO::FETCH_ASSOC);

			$email = $res['email'];
			$mitteilung = "Grüezi\n\nIhr Zugang in unseren passwortgeschützten Bereich unter http://{$_SERVER['SERVER_NAME']}/backend/ wurde leider verweigert. Vielen Dank für Ihr Verständnis.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

			if (isset($_GET['send'])) {

				if (!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
					$fehlerArr[] = 'Sie haben keine Mitteilung geschrieben.';
				} else {
					$mitteilung = $_POST['mitteilung'];
				}

				if (count($fehlerArr) == 0) {

					$this->db->prepareAndExecute("UPDATE benutzer SET aktiv=1, denied=NOW(), accepted='0000-00-00 00:00:00' WHERE ID=?", [$ID]);

					$to = $email;
					$toName = $email;
					$from = "webmaster@bsv-buelach.ch";
					$fromName = "webmaster@bsv-buelach.ch";
					$subject = 'Ihr Zugang wurde verweigert';

					(new FormMailer())->send($to, $toName, $from, $fromName, $subject, $mitteilung);
					$this->showPage->redirect("benutzerDet-{$ID}.html");
				}
			}
		}

		if (count($fehlerArr) != 0) {
			$status = '<div id="formfehler"><ul>';
			foreach ($fehlerArr as $val) {
				$status .= '<li>' . $val . '</li>';
			}
			$status .= '</ul></div>';
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['mitteilung'] = $mitteilung;
		$this->placeholders['ID'] = $ID;
	}
}