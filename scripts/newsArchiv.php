<?php

namespace scripts;

use classes\pageClass;

class newsArchiv extends pageClass
{
	public function execute()
	{
		$news = '';

		$cond = "n.archiv=? AND n.typ=?";
		$paramsArr[] = 1;
		$paramsArr[] = 1;

		$pos = 0;

		$fn = "newsfilter1";

		if (isset($_GET['filter']) && $_GET['filter'] == 'reset' && isset($_SESSION[$fn])) {
			unset($_SESSION[$fn]);
		}
		if (isset($_GET['pos'])) {
			$_SESSION[$fn]['pos'] = (is_numeric($_GET['pos'])) ? $_GET['pos'] : 0;
		}
		if (isset($_SESSION[$fn]['pos'])) {
			$pos = (int)$_SESSION[$fn]['pos'];
		}
		if ($pos < 0) {
			$pos = 0;
		}

		$sql = "
SELECT
  COUNT(n.ID) AS anz
  
FROM
  news n
  
WHERE
  {$cond}
";
		$qry = $this->db->query($sql, $paramsArr);
		$res = $qry->fetchObject();
		if ($res->anz == 0) {
			$news = "<p>Es gibt keine archivierten Neuigkeiten.</p>";
		} else {

			$pagination = $this->showPage->getPagenavi("newsArchiv", $res->anz, $pos);
			$news .= $pagination;
			$entriesPerPage = (int)$this->showPage->config['lists']['entriesPerPage'];
			$sql = "
  SELECT
    n.ID, n.titel, n.teaser, n.text
 
  FROM
    news n
  ";
			$sql .= "WHERE
    {$cond}
  
  ORDER BY
    n.datum DESC

  LIMIT
    {$pos}, {$entriesPerPage}";

			$qry = $this->db->query($sql, $paramsArr);
			while ($res = $qry->fetchObject()) {

				$news .= "<div class=\"startnews group\"><h3>{$res-> titel}</h3>{$res -> teaser}";
				if ($res->text != "") {
					$href = "newsDetails-{$res -> ID}.html";
					$news .= "<p><a href=\"{$href}\">weitere Informationen</a></p>";
				}
				$news .= "</div>\n";
			}
			$news .= $pagination;
		}

		$this->placeholders['news'] = $news;
	}
}

/* EOF */