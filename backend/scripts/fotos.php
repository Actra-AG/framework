<?php namespace backend\scripts;

use classes\pageClass;
use PDO;

class fotos extends pageClass
{
	public function execute() {
		$status = '';
		$ID = '';
		$liste = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {

			$ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

			$sql = "
  SELECT
    titel AS album
    
  FROM
    alben
    
  WHERE
   ID=?";
			$qry = $this->db->query($sql, [$ID]);
			if ($qry->rowCount() != 1) {
				$this->showPage->redirect("alben.html");
			}
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			$this->showPage->pageArr['platzhalter']['title'] = $res['album'];
			$this->showPage->pageArr['grundkonf']['navigator']['title'] = $res['album'];

			if (isset($_GET['add'])) {
				$status = '<p class="note-pos">Der Eintrag wurde hinzugefügt.</p>';
			}
			if (isset($_GET['mod'])) {
				$status = '<p class="note-pos">Die Änderungen wurden gespeichert.</p>';
			}
			if (isset($_GET['del'])) {
				$sql = "SELECT typ FROM fotos WHERE ID=?";
				$qry = $this->db->query($sql, [$_GET['del']]);
				if ($qry->rowCount() == 1) {
					$res = $qry->fetch(PDO::FETCH_ASSOC);

					if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/galerie/foto{$_GET['del']}.{$res['typ']}")) {
						unlink($_SERVER['DOCUMENT_ROOT'] . "/galerie/foto{$_GET['del']}.{$res['typ']}");
					}
					if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/galerie/orig_foto{$_GET['del']}.{$res['typ']}")) {
						unlink($_SERVER['DOCUMENT_ROOT'] . "/galerie/orig_foto{$_GET['del']}.{$res['typ']}");
					}
					if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/galerie/tnfoto{$_GET['del']}.{$res['typ']}")) {
						unlink($_SERVER['DOCUMENT_ROOT'] . "/galerie/tnfoto{$_GET['del']}.{$res['typ']}");
					}
					$this->db->query("DELETE FROM fotos WHERE ID=?", [$_GET['del']]);
					$status = '<p class="note-pos">Der Eintrag wurde gelöscht.</p>';
				}
			}

			if (isset($_GET['up']) || isset($_GET['down'])) {
				$action = (isset($_GET['up'])) ? 'up' : 'down';
				$fotoID = (isset($_GET['up'])) ? $_GET['up'] : $_GET['down'];
				$qry = $this->db->query("SELECT pos FROM fotos WHERE ID=?", [$fotoID]);
				$res = $qry->fetch(PDO::FETCH_ASSOC);
				$newPos = $res['pos'];
				if ($action == 'up') {
					$newPos = $res['pos'] - 1.5;
				} else if ($action == "down") {
					$newPos = $res['pos'] + 1.5;
				}
				$newPos = str_replace(",", ".", $newPos);
				$this->db->query("UPDATE fotos SET pos=? WHERE ID=?", [$newPos, $fotoID]);

				$i = 0;
				$qry = $this->db->query("SELECT ID FROM fotos WHERE albumID=? ORDER BY pos", [$ID]);
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
					$i++;
					$fotoID = $res['ID'];
					$this->db->query("UPDATE fotos SET pos={$i} WHERE ID=?", [$fotoID]);
				}
			}

			$fn = 'adm_fotos' . $ID;

			$paramsArr = [];
			$cond = "WHERE albumID=?";
			$paramsArr[] = $ID;

			$fArr['foto']['attributes'] = '';
			$fArr['foto']['order'] = 0;
			$fArr['foto']['ox'] = '';
			$fArr['foto']['value'] = 'Foto';

			$fArr['text']['attributes'] = '';
			$fArr['text']['order'] = 0;
			$fArr['text']['ox'] = '';
			$fArr['text']['value'] = 'Beschreibung';

			$fArr['groesse']['attributes'] = '';
			$fArr['groesse']['order'] = 0;
			$fArr['groesse']['ox'] = '';
			$fArr['groesse']['value'] = 'Grösse';

			$fArr['position']['attributes'] = '';
			$fArr['position']['order'] = 0;
			$fArr['position']['ox'] = '';
			$fArr['position']['value'] = 'Position';

			$fArr['action']['attributes'] = '';
			$fArr['action']['order'] = 0;
			$fArr['action']['ox'] = '';
			$fArr['action']['value'] = '&nbsp;';

			$pos = 0;
			$ox = "ASC";
			$orderby = "f.pos";
			if (isset($_GET['pos'])) {
				$_SESSION[$fn]['pos'] = $_GET['pos'];
			}
			if (isset($_GET['orderby']) && isset($fArr[$_GET['orderby']])) {
				$_SESSION[$fn]['orderby'] = urldecode($_GET['orderby']);
			}
			if (isset($_GET['ox']) && ($_GET['ox'] == 'ASC' || $_GET['ox'] == 'DESC')) {
				$_SESSION[$fn]['ox'] = $_GET['ox'];
			}
			if (isset($_SESSION[$fn]['pos'])) {
				$pos = (int)$_SESSION[$fn]['pos'];
			}
			if (isset($_SESSION[$fn]['orderby'])) {
				$orderby = $_SESSION[$fn]['orderby'];
			}
			if (isset($_SESSION[$fn]['ox'])) {
				$ox = $_SESSION[$fn]['ox'];
			}

			$sql = "
  SELECT
    COUNT(f.ID) AS anz
  
  FROM
    fotos f
  
  "."{$cond}
  
  ";
			$qry = $this->db->query($sql, $paramsArr);
			$res = $qry->fetchObject();
			$anz = $res->anz;
			if ($anz == 0) {
				$liste = "<p>Es wurden keine Einträge gefunden.</p>";
			} else {

				$pagination = $this->showPage->getPagenavi("fotos-{$ID}", $anz, $pos);
				$liste = "<p class=\"searchresult\">Es wurde(n) <strong>{$res -> anz}</strong> Resultat(e) gefunden.</p>";
				$liste .= $pagination;

				$liste .= "<div class=\"tablewrap\"><table cellspacing=\"0\" class=\"normtabelle\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";

				$sql = "
    SELECT
      f.ID, f.typ, f.text
      
    FROM
      fotos f
      
    "."{$cond}
    
    ORDER BY
      {$orderby} {$ox}

    LIMIT
      {$pos}, {$this->showPage->config['lists']['entriesPerPage']}";

				$i = 0;
				$qry = $this->db->query($sql, $paramsArr);
				while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

					$i++;

					$pArr = [];
					if ($i != 1) {
						$href = "fotos-{$ID}.html?up={$res['ID']}";
						$pArr[] = "<li class=\"oben\"><a href=\"{$href}\">nach&nbsp;oben</a></li>";
					}
					if ($i != $anz) {
						$href = "fotos-{$ID}.html?down={$res['ID']}";
						$pArr[] = "<li class=\"unten\"><a href=\"{$href}\">nach&nbsp;unten</a></li>";
					}

					$size = '';
					$foto = '';
					if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $res['ID'] . '.' . $res['typ'])) {

						$size = $this->showPage->bytestostring(filesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.' . $res['typ']));
						$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $res['ID'] . '.' . $res['typ']);
						$href = "/galerie/foto{$res['ID']}.{$res['typ']}";
						$src = "/galerie/tnfoto{$res['ID']}.{$res['typ']}";
						$foto = "<a href=\"{$href}\"><img src=\"{$src}\" {$imgArr[3]} alt=\"\" /></a>";
					}
					$liste .= "<tr>\n<td>{$foto}</td>\n<td>{$res['text']}</td>\n<td>{$size}</td>\n<td class=\"pos\">";
					if (count($pArr) == 0) {
						$liste .= "&nbsp;";
					} else {
						$liste .= "<ul>";
						foreach ($pArr AS $val) {
							$liste .= $val;
						}
						$liste .= "</ul>\n";
					}
					$href1 = "fotoMod-{$ID}-{$res['ID']}.html";
					$href2 = "fotos-{$ID}.html?del={$res['ID']}";
					$liste .= "</td>\n<td class=\"aktion\">\n<ul>\n<li><a href=\"{$href1}\" class=\"edit\">bearbeiten</a></li>\n<li><a href=\"{$href2}\" class=\"delete\">löschen</a></li>\n</ul>\n</td>\n</tr>\n";
				}
				$liste .= "</tbody>\n</table></div>";
				$liste .= $pagination;
			}
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $key => $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['liste'] = $liste;
	}
}


/* EOF */