<?php

namespace scripts;

use classes\pageClass;
use PDO;

class newsDetails extends pageClass
{
	public function execute()
	{
		$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

		$sql = "SELECT titel, teaser, text FROM news WHERE ID='{$ID}'";
		$qry = $this->db->prepareAndExecute($sql);
		if ($qry->rowCount() != 1) {
			$this->showPage->redirect("newsArchiv.html");
		}
		$res = $qry->fetch(PDO::FETCH_ASSOC);

		$news = "<div class=\"startnews group\"><h3>{$res['titel']}</h3>{$res['teaser']}{$res['text']}</div>\n";

		$titel = $res['titel'];
		$this->showPage->pageArr['platzhalter']['title'] = $titel;
		$this->showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

		$this->placeholders['titel'] = $titel;
		$this->placeholders['news'] = $news;
	}
}

/* EOF */