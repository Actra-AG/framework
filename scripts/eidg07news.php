<?php
namespace scripts;

use classes\pageClass;
use PDO;

class eidg07news extends pageClass
{
	public function execute()
	{
		$sql = "SELECT n.ID, n.titel, n.teaser, n.text, DATE_FORMAT(n.datum, '%d.%m.%Y') AS datum, DATE_FORMAT(n.datum, '%T') AS zeit FROM news n WHERE n.archiv='0' AND n.typ=2 ORDER BY n.datum DESC";
		$qry = $this->db->query($sql);
		if ($qry->rowCount() == 0) {
			$eidgnews = '<p class="noentry">keine Neuigkeiten</p>';

		} else {
			$eidgnews = '';
			$lastday = '';
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				if ($res['datum'] != $lastday) {
					if ($lastday != '') {
						$eidgnews .= '</ul>';
					}
					$eidgnews .= '<h3>' . $res['datum'] . '</h3><ul>';
				}
				$href = 'newsDetails-' . $res['ID'] . '.html';
				$eidgnews .= '<li><span><a href="'.$href.'">' . $res['titel'] . '</a></span> <em>' . $res['zeit'] . '</em></li>';
				$lastday = $res['datum'];
			}
			$eidgnews .= '</ul>';
		}

		$this->placeholders['eidgnews'] = $eidgnews;
	}
}

/* EOF */