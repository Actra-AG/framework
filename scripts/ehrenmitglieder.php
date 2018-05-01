<?php
namespace scripts;

use classes\pageClass;
use PDO;

class ehrenmitglieder extends pageClass
{
	public function execute()
	{
		$ehren = '';

		$sql = "SELECT * FROM benutzer WHERE ehren=1 ORDER BY ernannt, nachname, vorname";
		$qry = $this->db->query($sql);
		while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
			$ehren .= "<tr><td>{$res['nachname']}</td><td>{$res['vorname']}</td><td>{$res['plz']} {$res['ort']}</td><td>{$res['ernannt']}</td></tr>\n";
		}

		$this->placeholders['ehren'] = $ehren;
	}
}
/* EOF */