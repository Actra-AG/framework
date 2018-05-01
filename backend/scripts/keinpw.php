<?php

namespace backend\scripts;

use classes\pageClass;
use classes\FormMailer;

class keinpw extends pageClass
{
	public function execute()
	{
		$status = '';
		$pwlookup = '';

		$fehlerArr = [];

		if (!$this->showPage->checkAccess()) {

			if (isset($_POST['pwlookup']) && isset($_GET['send'])) {

				$pwlookup = $_POST['pwlookup'];

				if ($_POST['pwlookup'] == '') {
					$fehlerArr[] = 'Bitte geben Sie Ihre E-Mail-Adresse ein';
				} else {
					$sql = "
		  SELECT
  		  b.email, b.ID
		  
		  FROM
  		  benutzer b

		  
		  WHERE
  		  b.email=?";
					$qry = $this->db->query($sql, [$_POST['pwlookup']]);
					if ($qry->rowCount() == 1) {
						$res = $qry->fetchObject();

						$to = $res->email;
						$toName = $res->email;
						$from = "webmaster@bsv-buelach.ch";
						$fromName = "webmaster@bsv-buelach.ch";
						$subject = 'Ihr neues Passwort';

						$code = md5("bsvpas{$res -> ID}buelach");

						$text = "Grüezi\n\nSie haben bei {$_SERVER['SERVER_NAME']} angegeben, dass Sie das Passwort vergessen haben.\n\nKlicken Sie auf den folgenden Link, um ein neues Passwort zu wählen:\n{$this->showPage -> config['protocol']}://{$_SERVER['SERVER_NAME']}/backend/passwort-{$res -> ID}-{$code}.html\n\nWenn Sie Ihr Passwort nicht vergessen haben, ignorieren Sie diese E-Mail und klicken Sie nicht auf den obigen Link!\n\nFreundliche Grüsse\n\nBezirksschützenverband Bülach";

						(new FormMailer())->send($to, $toName, $from, $fromName, $subject, $text);
						$this->showPage->redirect("keinpwRes.html");
					} else {
						$fehlerArr[] = 'Mit dieser E-Mail-Adresse existiert kein Zugang';
					}
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
		$this->placeholders['pwlookup'] = $pwlookup;
	}
}
/* EOF */