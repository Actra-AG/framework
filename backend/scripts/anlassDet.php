<?php
$bsvb = new bsvb();

$status = '';
$confirm = '';
$modlink = '';
$kategorien = '';
$dokumente = '';

$datenArr['verein'] = '';
$datenArr['datum'] = '';
$datenArr['titel'] = '';
$datenArr['ort'] = '';
$datenArr['zeit'] = '';
$datenArr['bemerkungen'] = '';
$datenArr['erfasst'] = '';
$datenArr['lastmod'] = '';
$datenArr['xs'] = '';

$jpArr = $bsvb->getJahresprogramm();

if ($showPage->checkUG('aktiv')) {

    $myID = $showPage->userData->ID;
    $ID = (isset($showPage->arrVars[1])) ? $showPage->arrVars[1] : 0;

    if (isset($_GET['deny']) && $showPage->checkUG('admin')) {
        $DB_LINK->query("UPDATE jahresprogramm SET denied=NOW(), confirmed='0000-00-00 00:00:00' WHERE ID='{p}'", array($ID));
    }


    $sql = "
	SELECT
	  v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.zeit, p.bemerkungen, CONCAT(IF(p.registered='0000-00-00', '', DATE_FORMAT(p.registered, '%d.%m.%Y')), ' von ', b.vorname, ' ', b.nachname) AS erfasst, IF(p.lastmod='0000-00-00', '', DATE_FORMAT(p.lastmod, '%d.%m.%Y')) AS lastmod, IF(p.confirmed!='0000-00-00 00:00:00', 'aktiv', IF(p.denied!='0000-00-00 00:00:00', 'abgelehnt', 'zu prüfen')) AS xs, p.registered_by, p.gm300, p.gm50, p.gm25, p.gm10, p.mw300, p.mw50, p.mwlg, p.mwlp, p.mwba, p.js, p.vt, p.sa300, p.sa50, p.sa25, p.sa10, p.vs, p.wb, p.vorstand
	    , IF(p.export=1, 'ja', 'nein') AS export
	      , zeitVon
  , zeitBis

	FROM
	  jahresprogramm p
	  LEFT JOIN vereine v ON p.vereinID=v.ID
	  LEFT JOIN benutzer b ON p.registered_by=b.ID

	WHERE
	  p.ID='{p}'
	";
    $qry = $DB_LINK->query($sql, array($ID));
    if ($qry->num_rows() == 0) {
        $showPage->redirect("jp.html");
    }
    $datenArr = $qry->fetch_assoc();
    $datenArr['datum'] = ($datenArr['datumVon'] == $datenArr['datumBis']) ? $datenArr['datumVon'] : "{$datenArr['datumVon']} - {$datenArr['datumBis']}";

    if ($datenArr['registered_by'] == $myID || $showPage->userData->admin == 1) {
        $modlink = "<p class=\"link-edit\"><a href=\"anlassMod-{$ID}.html\"><span>Details ändern</span></a></p><p class=\"link-delete\"><a href=\"jp.html?remove={$ID}\" class=\"delete\"><span>Anlass löschen</span></a></p>";

        if (isset($_GET['delDok'])) {

            $sql = "
      SELECT
        f.extension

      FROM
        dokumente d
        INNER JOIN dateiformate f ON d.type=f.mimetype

      WHERE
        d.ID='{p}'
      ";
            $qry = $DB_LINK->query($sql, array($_GET['delDok']));
            $res = $qry->fetch_assoc($qry);
            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $_GET['delDok'] . '.' . $res['extension'])) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $_GET['delDok'] . '.' . $res['extension']);
            }
            $DB_LINK->query("DELETE FROM dokumente WHERE ID='{p}'", array($_GET['delDok']));
        }
    }

// 	if($datenArr['xs'] == 'zu prüfen') {

    if ($datenArr['xs'] == 'aktiv') {
        if ($showPage->checkUG('admin')) {
            $confirm = "<p>Anlass <a href=\"anlassDet-{$ID}.html?deny\">deaktivieren</a></p>";

        } else {
            $confirm = "<p>Dieser Anlass wurde durch einen Administratoren geprüft und veröffentlicht.</p>";

        }
    } else {
        if ($showPage->checkUG('admin')) {
            $confirm = "<p>Anlass <a href=\"publicate-{$ID}.html\">publizieren</a></p>";

        } else {
            $confirm = "<p>Dieser Anlass wird veröffentlicht, sobald er durch einen Administratoren geprüft wurde.</p>";

        }
    }

    $katArr = array();
    foreach ($jpArr['typen'] AS $typ => $typData) {
        if (isset($datenArr[$typ]) && $datenArr[$typ] == 1) {
            $katArr[] = "<li>{$typData['titel']}</li>\n";
        }
    }

    if (count($katArr) != 0) {
        $kategorien = "<ul>" . implode("", $katArr) . "</ul>\n";
    }

    $pdfArr = array();

    $sql = "
  SELECT
    d.ID, d.dateiname, d.titel, f.extension

  FROM
    dokumente d
    INNER JOIN dateiformate f ON d.type=f.mimetype

  WHERE
    d.objekt='anlass' AND d.objektID='{p}'

  ORDER BY
    titel";
    $qry = $DB_LINK->query($sql, array($ID));
    while ($res = $qry->fetch_assoc($qry)) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $res['ID'] . '.' . $res['extension'])) {
            $size = $showPage->bytestostring(filesize($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $res['ID'] . '.' . $res['extension']));
            $key = md5("aasmdsjtk{$res['ID']}asujdt3?nz34g");

            $doktitel = ($res['titel'] == '') ? 'ohne Titel' : $res['titel'];
            $pdfArr[] = "<li><span><a href=\"/dokumente/{$res['ID']}/{$key}/" . urlencode($res['dateiname']) . "\">{$doktitel}</a></span> <ul class=\"actionlinks\"><li><a href=\"dokumentMod-anlass-{$ID}-{$res['ID']}.html\" class=\"edit\">bearbeiten</a></li><li><a class=\"delete\" href=\"anlassDet-{$ID}.html?delDok={$res['ID']}\">löschen</a></li></ul>\n";
        }
    }

    if ($datenArr['registered_by'] == $myID || $showPage->userData->admin == 1) {
        $dokumente .= "<p class=\"link-add\"><a href=\"dokumentMod-anlass-{$ID}-0.html\"><span>Dokument hochladen</span></a></p>\n";

    }

    if (count($pdfArr) != 0) {
        $dokumente .= "<ul>\n" . implode("\n", $pdfArr) . "</ul>";

    } else {
        $dokumente .= "<p>Zu diesem Anlass gibt es keine Dokumente.</p>";

    }

}

$platzhalter['status'] = $status;
$platzhalter['confirm'] = $confirm;
$platzhalter['modlink'] = $modlink;
$platzhalter['kategorien'] = $kategorien;
$platzhalter['dokumente'] = $dokumente;

foreach ($datenArr AS $key => $val) {
    $platzhalter[$key] = $val;
}
?>