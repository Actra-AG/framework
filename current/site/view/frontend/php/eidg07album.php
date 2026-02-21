<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class eidg07album extends FrontendView
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
        return 'Fotos zum eidgenössischen Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $eidgalbum = '';

        $sql = "SELECT * FROM alben WHERE typ=2 ORDER BY titel";
        $qry = $this->db->prepareAndExecute($sql);
        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
            $href = "eidg07fotos-{$res['ID']}.html";
            $eidgalbum .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
        }

        $this->placeholders['eidgalbum'] = $eidgalbum;
    }
}
