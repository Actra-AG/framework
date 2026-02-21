<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class foto extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start',
            2 => 'fotogalerie'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Fotogalerie';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $navi = '';
        $foto = '';
        $text = '';

        $albumID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;
        $fotoID = (isset($this->showPage->arrVars[2])) ? $this->showPage->arrVars[2] : 0;

        $sql = "SELECT titel FROM alben WHERE ID=?";
        $qry = $this->db->prepareAndExecute($sql, [$albumID]);
        if ($qry->rowCount() != 1) {
            $this->showPage->redirect("alben.html");
        }
        $res = $qry->fetch(PDO::FETCH_ASSOC);

        $titel = $res['titel'];
        $this->showPage->pageArr['platzhalter']['title'] = $titel;
        $this->showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

        $sql = "SELECT f.text, f.typ, (SELECT ID FROM fotos WHERE albumID=f.albumID AND pos<f.pos ORDER BY pos DESC LIMIT 1) AS lastID, (SELECT ID FROM fotos WHERE albumID=f.albumID AND pos>f.pos ORDER BY pos LIMIT 1) AS nextID FROM fotos f WHERE f.ID=?";
        $qry = $this->db->prepareAndExecute($sql, [$fotoID]);
        if ($qry->rowCount() != 1) {
            $this->showPage->redirect("fotos-{$albumID}.html");
        }
        $res = $qry->fetch(PDO::FETCH_ASSOC);

        if ($res['lastID'] != '') {
            $href = "foto-{$albumID}-{$res['lastID']}.html";
            $navi .= "<li><a href=\"{$href}\">&laquo; zurück</a></li>\n";
        }
        if ($res['nextID'] != '') {
            $href = "foto-{$albumID}-{$res['nextID']}.html";
            $navi .= "<li><a href=\"{$href}\">vor &raquo;</a></li>\n";
        }

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.' . $res['typ'])) {
            $imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.' . $res['typ']);
            $src = "/galerie/foto{$fotoID}.{$res['typ']}";
            $foto = "<div id=\"img\"><img src=\"{$src}\" {$imgArr[3]} alt=\"\" /></div>";
        }

        if ($res['text'] != '') {
            $text = "<p class=\"legende\">" . nl2br($res['text']) . "</p>";
        }

        $this->placeholders['titel'] = $titel;
        $this->placeholders['albumID'] = $albumID;
        $this->placeholders['navi'] = $navi;
        $this->placeholders['foto'] = $foto;
        $this->placeholders['text'] = $text;
    }
}
