<?php namespace backend\scripts;

use classes\pageClass;
use classes\bsvb;
use PDO;
use classes\ImageResize;

class fotoMod extends pageClass
{
	public function execute()
	{
		$bsvb = new bsvb();

		$status = '';
		$albumID = '';
		$ID = '';
		$foto = '';

		$datenArr['text'] = '';
		$datenArr['typ'] = '';

		$fehlerArr = [];

		if ($this->showPage->checkUG('redaktor')) {

			$albumID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;
			$ID = (isset($this->showPage->arrVars[2])) ? $this->showPage->arrVars[2] : 0;

			$sql = "
  SELECT
    titel AS album

  FROM
    alben

  WHERE
   ID=?";
			$qry = $this->db->prepareAndExecute($sql, [$albumID]);
			if ($qry->rowCount() != 1) {
				$this->showPage->redirect("alben.html");
			}
			$sql = "SELECT text, typ FROM fotos WHERE ID=?";
			$qry = $this->db->prepareAndExecute($sql, [$ID]);
			if ($qry->rowCount() == 1) {
				$this->showPage->pageArr['platzhalter']['title'] = 'Foto bearbeiten';
				$this->showPage->pageArr['grundkonf']['navigator']['title'] = 'Foto bearbeiten';
				$ac = 'mod';
				$datenArr = $qry->fetch(PDO::FETCH_ASSOC);
			} else {

				$ID = 0;
				$ac = 'add';
				$this->showPage->pageArr['platzhalter']['title'] = 'Foto hinzufügen';
				$this->showPage->pageArr['grundkonf']['navigator']['title'] = 'Foto hinzufügen';
			}

			if ($ID == 0) {
				$tempID = session_id();
			} else {
				$tempID = $ID;
			}

			if (isset($_GET['send'])) {

				if (isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
					$imgArr = getimagesize($_FILES['foto']['tmp_name']);
					$mime = strtolower($imgArr['mime']);
					$sql = "SELECT extension FROM dateiformate WHERE mimetype=? AND FIND_IN_SET('foto', arten)!=0";
					$qry = $this->db->prepareAndExecute($sql, [$mime]);
					if ($qry->rowCount() != 1) {
						$fehlerArr[] = 'Leider ist das Foto in einem ungültigen Dateiformat (' . $mime . ').';
					} else {
						$res = $qry->fetch(PDO::FETCH_ASSOC);
						$datenArr['typ'] = $res['extension'];

						$h = 90;
						$w = 125;
						/*     		if($imgArr[0] < $imgArr[1]) {
										 $h = 160;
										 $w = 120;
									 }*/

						$imgRes = new ImageResize();
						$imgRes->resize_image($_FILES['foto']['tmp_name'], $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'] . '/galerie/', 'foto' . $tempID, 510);
						$imgRes->resize_image($_SERVER['DOCUMENT_ROOT'] . '/galerie/orig_foto' . $tempID . '.' . $datenArr['typ'], $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'] . '/galerie/', 'tnfoto' . $tempID, $w, $h, 90, 1, 0);
						if ($ac == 'mod') {
							$this->db->prepareAndExecute("UPDATE fotos SET typ=? WHERE ID=?", [$datenArr['typ'], $tempID]);
						}
					}
				} else if ($ac == 'add') {
					$fehlerArr[] = 'Sie haben kein Foto ausgewählt.';
				}

				if (isset($_POST['text'])) {
					$datenArr['text'] = $_POST['text'];
				}

				if (count($fehlerArr) == 0) {

					if ($ID == 0) {
						$sql = "SELECT MAX(pos)+1 AS pos FROM fotos WHERE albumID=?";
						$qry = $this->db->prepareAndExecute($sql, [$albumID]);
						$res = $qry->fetch(PDO::FETCH_ASSOC);
						$datenArr['albumID'] = $albumID;
						$datenArr['pos'] = $res['pos'];

						$ID = $bsvb->insertEntry('fotos', $datenArr);

						if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $tempID . '.' . $datenArr['typ'])) {
							rename($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $tempID . '.' . $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $ID . '.' . $datenArr['typ']);
						}
						if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/orig_foto' . $tempID . '.' . $datenArr['typ'])) {
							rename($_SERVER['DOCUMENT_ROOT'] . '/galerie/orig_foto' . $tempID . '.' . $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'] . '/galerie/orig_foto' . $ID . '.' . $datenArr['typ']);
						}
						if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $tempID . '.' . $datenArr['typ'])) {
							rename($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $tempID . '.' . $datenArr['typ'], $_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $ID . '.' . $datenArr['typ']);
						}
					} else {
						$bsvb->updateEntry('fotos', $ID, $datenArr);
					}

					$this->showPage->redirect("fotos-{$albumID}-{$ID}.html?{$ac}");
				}
			}

			$fototitel = 'Foto';
			$fotozusatz = '';
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $tempID . '.' . $datenArr['typ'])) {
				$imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/tnfoto' . $tempID . '.' . $datenArr['typ']);
				$src = "/galerie/tnfoto{$tempID}.{$datenArr['typ']}?time=" . time();
				$foto = "<dl><dt>Aktuelles Foto</dt><dd><img src=\"{$src}\" {$imgArr[3]} alt=\"\" /> <!--<input type=\"submit\" class=\"submit\" name=\"fotoDel\" value=\"löschen\" />--></dd>\n</dl>";
				$fototitel = 'Neues Foto';
				$fotozusatz = " Lassen Sie dieses Feld leer, wenn Sie das bestehende Foto behalten möchten.";
			}
			$foto .= "<dl><dt><label for=\"pfoto\">{$fototitel}</label></dt><dd><input type=\"file\" class=\"file\" name=\"foto\" id=\"pfoto\" />\n<em>Kann im *.jpg, *.gif oder *.png-Format sein. Wird automatisch verkleinert und zugeschnitten.{$fotozusatz}</em></dd><!--<input type=\"submit\" class=\"submit\" name=\"fotoAdd\" value=\"hochladen\" />--></dl>";
		}

		if (count($fehlerArr) != 0) {
			$status = "<div id=\"formfehler\"><ul>\n";
			foreach ($fehlerArr as $val) {
				$status .= "<li>{$val}</li>\n";
			}
			$status .= "</ul></div>";
		}

		$this->placeholders['status'] = $status;
		$this->placeholders['albumID'] = $albumID;
		$this->placeholders['ID'] = $ID;
		$this->placeholders['foto'] = $foto;

		foreach ($datenArr as $key => $val) {
			$this->placeholders[$key] = htmlentities($val);
		}
	}
}