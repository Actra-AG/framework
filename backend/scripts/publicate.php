<?php namespace backend\scripts;

use classes\pageClass;
use PDO;
use classes\FormMailer;

class publicate extends pageClass
{
	public function execute() {
		$status = '';
		$mitteilung = '';
		$ID = 0;

		$fehlerArr = [];

		if ($this->showPage->checkUG('admin')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;
			$sql = "SELECT p.titel, b.anrede, b.nachname, b.email FROM jahresprogramm p INNER JOIN benutzer b ON p.registered_by=b.ID WHERE p.ID=?";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() == 0) {
				$this->showPage->redirect("jp.html");
			}
			$res = $qry->fetch(PDO::FETCH_ASSOC);

			$titel = $res['titel'];
			$email = $res['email'];
			$mitteilung = "Grüezi\n\nDer von Ihnen erfasste Anlass \"{$titel}\" wurde von einem Administrator geprüft und auf unserer Website {$_SERVER['SERVER_NAME']} veröffentlicht.\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

			if (isset($_GET['send'])) {

				if (!isset($_POST['mitteilung']) || $_POST['mitteilung'] == '') {
					$fehlerArr[] = 'Sie haben keine Mitteilung geschrieben.';
				} else {
					$mitteilung = $_POST['mitteilung'];
				}

				if (count($fehlerArr) == 0) {

					$this->db->query("UPDATE jahresprogramm SET confirmed=NOW(), denied='0000-00-00 00:00:00' WHERE ID=?", [$ID]);

					$to = $email;
					$toName = $email;
					$from = "webmaster@bsv-buelach.ch";
					$fromName = "webmaster@bsv-buelach.ch";
					$subject = 'Ihr Anlass wurde freigeschaltet';

					(new FormMailer())->send($to, $toName, $from, $fromName, $subject, $mitteilung);
					$this->showPage->redirect("anlassDet-{$ID}.html");
				}
			}
		}

		if (count($fehlerArr) != 0) {
			$status = '<div id="formfehler"><ul>';
			foreach ($fehlerArr as $key => $val) {
				$status .= '<li>' . $val . '</li>';
			}
			$status .= '</ul></div>';
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['mitteilung'] = $mitteilung;
		$this->placeholders['ID'] = $ID;
	}
}


/* EOF */