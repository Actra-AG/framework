<?php

namespace scripts;

use classes\pageClass;
use PDO;

class kontaktRes extends pageClass
{
	public function execute()
	{
		$toID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

		$this->showPage->pageArr['platzhalter']['title'] = 'BSVB kontaktieren';

		$sql = "
SELECT
  vorname, nachname, email
  
FROM
  benutzer
  
WHERE
  ID=?
";
		$qry = $this->db->prepareAndExecute($sql, [$toID]);
		if ($qry->rowCount() == 1) {
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			$this->showPage->pageArr['platzhalter']['title'] = "{$res['vorname']} {$res['nachname']} kontaktieren";
		}
	}
}
/* EOF */