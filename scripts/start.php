<?php

namespace scripts;

use classes\pageClass;
use PDO;

class start extends pageClass
{
	public function execute()
	{
		$news = '';

		$sql = "SELECT * FROM news WHERE archiv=0 AND typ=1 ORDER BY datum DESC";
		$qry = $this->db->prepareAndExecute($sql);
		if ($qry->rowCount() == 0) {
			$news = "<p>Zurzeit gibt es keine Neuigkeiten.</p>";
		} else {
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

				$news .= "<div class=\"startnews group\"><h3>{$res['titel']}</h3>{$res['teaser']}<p>\n";
				if ($res['text'] != "") {
					$href = "newsDetails-{$res['ID']}.html";
					$news .= "<a href=\"{$href}\">weitere Informationen</a></p>\n";
				}
				$news .= "</div>\n";
			}
		}

		$this->placeholders['news'] = $news;
	}
}

/* EOF */