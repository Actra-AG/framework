<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class confirm extends pageClass
{
	public function execute()
	{
		if (!isset($this->showPage->arrVars[2])) {
			$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";
		} else {
			$ID = $this->showPage->arrVars[1];
			$code = $this->showPage->arrVars[2];

			if ($code != md5("bsvregister{$ID}buelach")) {
				$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";
			} else {
				$sql = "SELECT confirmed, DATE_FORMAT(confirmed, '%d.%m.%Y') AS cf, accepted FROM benutzer WHERE ID=?";
				$qry = $this->db->query($sql, [$ID]);
				if ($qry->rowCount() != 1) {
					$status = "<p class=\"error\">Es wurde ein ungültiger Link geöffnet.</p>";
				} else {
					$res = $qry->fetch(PDO::FETCH_ASSOC);
					if ($res['confirmed'] == '0000-00-00 00:00:00') {
						$this->db->query("UPDATE benutzer SET confirmed=NOW() WHERE ID=?", [$ID]);
						$status = "<p>Ihr Zugang wurde aktiviert.</p>";
					} else {
						$status = "<p class=\"error\">Dieser Zugang wurde bereits am {$res['cf']} aktiviert.</p>";
					}
					if ($res['accepted'] == '0000-00-00 00:00:00') {
						$status .= "<p>Sie werden benachrichtigt, sobald wir Ihren Zugang freigeschaltet haben.</p>";
					}
				}
			}
		}

		$this->placeholders['status'] = $status;
	}
}

/* EOF */