<?php

namespace scripts;

use classes\ErrorHandler;
use classes\pageClass;
use PDO;

class eidg07fotos extends pageClass
{
	public function execute(): void
	{
		$katID = 0;
		if (isset($this->showPage->arrVars[1])) {
			$katID = (int)$this->showPage->arrVars[1];
		}
		$album = $this->db->prepareAndExecute(
			sql: 'SELECT titel FROM alben WHERE ID=?',
			parameters: [$katID]
		)->fetch(mode: PDO::FETCH_OBJ);
		if ($album === false) {
			ErrorHandler::display_error(errCode: 404);
		}
		$fotos = '';
		$qry = $this->db->prepareAndExecute(
			sql: 'SELECT * FROM fotos WHERE albumID=? ORDER BY pos',
			parameters: [$katID]
		);
		if ($qry->rowCount() === 0) {
			$fotos = '<p class="noentry">In dieser Kategorie gibt es noch keine Fotos.</p>';
		} else {
			$fotos .= "<ul id=\"galerie\">\n";
			while ($picture = $qry->fetch(mode: PDO::FETCH_OBJ)) {
				if (file_exists(filename: $_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $picture->ID . '.jpg')) {
					$href = 'eidg07foto-' . $katID . '-' . $picture->ID . '.html';
					$src = '/galerie/tnfoto' . $picture->ID . '.jpg';
					$fotos .= '<li><a href="' . $href . '"><img src="' . $src . '" width="125" height="90" alt="" /></a></li>' . PHP_EOL;
				}
			}
			$fotos .= '</ul>';
		}
		$this->placeholders['title'] = $album->titel;
		$this->placeholders['fotos'] = $fotos;
	}
}