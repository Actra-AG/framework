<?php
error_reporting(2047);

$reqArr = explode("/", $_SERVER['REQUEST_URI']);

$ok = 0;
$type = '';
$dateiname = '';
$file = '';

if (isset($reqArr[4])) {
	$ID = (int)$reqArr[2];
	$code = md5("aasmdsjtk{$ID}asujdt3?nz34g");

	if ($code != $reqArr[3]) {
		ErrorHandler::display_error(403);

	} else {
		$dateiname = $reqArr[4];

		$sql = "SELECT d.type, f.extension, d.dateiname FROM dokumente d INNER JOIN dateiformate f ON d.type=f.mimetype WHERE d.ID=?";
		$qry = $DB_LINK->query($sql, array($ID));
		if ($qry->rowCount() == 1) {
			$res = $qry->fetch(PDO::FETCH_ASSOC);
			$file = $_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $ID . '.' . $res['extension'];
			$type = $res['type'];
			$dateiname = $res['dateiname'];

			if (!file_exists($file)) {
				ErrorHandler::display_error(404);

			} else {
				$DB_LINK->query("UPDATE dokumente SET views=views+1 WHERE ID=?", array($ID));
				$ok = 1;
			}
		}
	}
}

if ($ok == 1) {
	header('Cache-Control: maxage=1');
	header("Pragma: public");
	header('Content-Type: ' . $type);
	header("Content-Disposition: attachment; filename={$dateiname};");
	header('Content-Length: ' . filesize($file));
	readfile($file);

} else {
	ErrorHandler::display_error(404);

}

$requestHandler->finish();
/* EOF */