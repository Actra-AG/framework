<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class archiv extends pageClass
{
	public function execute() {
		$backlink = '';
		$was = '';
		$version = '';
		$fields = '';

		if ($this->showPage->checkUG('redaktor') && isset($this->showPage->arrVars[2])) {

			$typ = $this->showPage->arrVars[1];
			$ID = $this->showPage->arrVars[2];

			if ($typ == 'seite') {

				$sql = "SELECT DATE_FORMAT(s.datum, '%d.%m.%Y %T') AS datum, s.ort, s.seite, s.inhalt, s.config FROM seiteninhalte s WHERE s.ID=?";
				$qry = $this->db->prepareAndExecute($sql, [$ID]);
				$res = $qry->fetch(PDO::FETCH_ASSOC);

				$backlink = "seiteArchiv-{$res['ort']}-{$res['seite']}.html";
				$was = "Seite {$res['seite']} ({$res['ort']})";
				$version = $res['datum'];

				$srcArr[1] = '<';
				$rplArr[1] = "&lt;";

				$srcArr[2] = '>';
				$rplArr[2] = "&gt;";

				$fields .= "<h3>Inhalt</h3>\n<pre>" . str_replace($srcArr, $rplArr, $res['inhalt']) . "</pre>";
				$fields .= "<h3>Konfiguration</h3>\n<pre>" . str_replace($srcArr, $rplArr, $res['config']) . "</pre>";
			}
		}

		$this->placeholders['backlink'] = $backlink;
		$this->placeholders['was'] = $was;
		$this->placeholders['version'] = $version;
		$this->placeholders['fields'] = $fields;
	}
}


/* EOF */