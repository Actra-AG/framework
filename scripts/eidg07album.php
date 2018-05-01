<?php

namespace scripts;

use classes\pageClass;
use PDO;

class eidg07album extends pageClass
{
	public function execute()
	{
		$eidgalbum = '';

		$sql = "SELECT * FROM alben WHERE typ=2 ORDER BY titel";
		$qry = $this->db->query($sql);
		while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
			$href = "eidg07fotos-{$res['ID']}.html";
			$eidgalbum .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
		}

		$this->placeholders['eidgalbum'] = $eidgalbum;
	}
}
/* EOF */