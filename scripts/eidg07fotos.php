<?php

namespace scripts;

use classes\pageClass;
use PDO;

class eidg07fotos extends pageClass
{
	public function execute()
	{
		$katID = 0;
		if (isset($this->showPage->arrVars[1])) {
			$katID = (int)$this->showPage->arrVars[1];
		}

		$sql = "SELECT titel FROM alben WHERE ID=?";
		$qry = $this->db->prepareAndExecute($sql, [$katID]);
		$res = $qry->fetch(PDO::FETCH_ASSOC);
		$title = $res['titel'];

		$fotos = '';
		$sql = "SELECT * FROM fotos WHERE albumID=? ORDER BY pos";
		$qry = $this->db->prepareAndExecute($sql, [$katID]);
		if ($qry->rowCount() == 0) {
			$fotos = '<p class="noentry">In dieser Kategorie gibt es noch keine Fotos.</p>';
		} else {
			$fotos .= "<ul id=\"galerie\">\n";
			while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
				if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.jpg')) {
					$href = "eidg07foto-{$katID}-{$res['ID']}.html";
					$src = "/galerie/tnfoto{$res['ID']}.jpg";
					$fotos .= "<li><a href=\"{$href}\"><img src=\"{$src}\" width=\"125\" height=\"90\" alt=\"\" /></a></li>\n";
				}
			}
			$fotos .= "</ul>";
		}

		$this->placeholders['title'] = $title;
		$this->placeholders['fotos'] = $fotos;
	}
}
/* EOF */