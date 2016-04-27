<?php
$foto = '';
$datum = '';
$eidgnews = '';

$datum = strftime("%A, %e. %B");


$sql = "SELECT n.ID, n.titel, n.teaser, n.text, DATE_FORMAT(n.datum, '%d.%m.%Y') AS datum, DATE_FORMAT(n.datum, '%T') AS zeit FROM news n WHERE n.archiv='0' AND n.typ=2 ORDER BY n.datum DESC LIMIT 0,3";
$qry = $DB_LINK->query($sql);
if ($qry->rowCount() == 0) {
	$eidgnews = '<span>keine Neuigkeiten</span>';

} else {
	$ldate = '';
	$eidgnews = '<ul>';
	while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {

		if ($ldate == $res['datum'] || $ldate == '') {
			$href = 'newsDetails-' . $res['ID'] . '.html';
			$eidgnews .= '<li><span><a href="'.$href.'">' . $res['titel'] . '</a></span> <em>' . $res['datum'] . '</em></li>';
			$ldate = $res['datum'];
		}
	}
	$eidgnews .= '</ul>';
}

$sql = "
SELECT
  f.ID AS fotoID, k.ID AS albumID
  
FROM
  fotos f
  INNER JOIN alben k ON f.albumID=k.ID
  
WHERE
  k.typ = 2

ORDER BY
  f.registered DESC
  
LIMIT 0,1
";
$qry = $DB_LINK->query($sql);
$res = $qry->fetch(PDO::FETCH_ASSOC);
$foto = '<a href="eidg07foto-' . $res['albumID'] . '-' . $res['fotoID'] . '.html"><img src="/gallery/tnfoto' . $res['fotoID'] . '.jpg" width="125" height="90" alt="" /></a>';

$platzhalter['foto'] = $foto;
$platzhalter['datum'] = $datum;
$platzhalter['eidgnews'] = $eidgnews;
/* EOF */