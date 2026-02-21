<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class eidg07foto extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07fotos'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidgenössisches 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $imgsize = '';
        $text = '';

        $katID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;
        $fotoID = (isset($this->showPage->arrVars[2])) ? $this->showPage->arrVars[2] : 0;

        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.jpg')) {
            $imgArr = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/galerie/foto' . $fotoID . '.jpg');
            $imgsize = $imgArr[3];

        }

        $sql = "SELECT titel FROM alben WHERE ID=?";
        $qry = $this->db->prepareAndExecute($sql, array($katID));
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        $title = $res['titel'];

        $sql = "SELECT text FROM fotos WHERE ID =?";
        $qry = $this->db->prepareAndExecute($sql, array($fotoID));
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        if ($res['text'] != '') {
            $text = '<p>' . nl2br($res['text']) . '</p>';
        }

        $this->placeholders['title'] = $title;
        $this->placeholders['katID'] = $katID;
        $this->placeholders['fotoID'] = $fotoID;
        $this->placeholders['imgsize'] = $imgsize;
        $this->placeholders['text'] = $text;
    }
}
