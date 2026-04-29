<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidg07fotos extends FrontendView
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
        return 'Fotos';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $katID = (int)($this->getPathVar(nr: 1) ?? 0);

        $sql = "SELECT titel FROM alben WHERE ID=?";
        $album = $this->db->prepareAndExecute($sql, [$katID]);
        $res = $album->fetchObject();
        
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $res->titel);

        $fotos = '';

        $sql = "SELECT * FROM fotos WHERE katID=? ORDER BY ID";
        $qry = $this->db->prepareAndExecute($sql, [$katID]);
        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $href = "eidg07foto-{$katID}-{$res['ID']}.html";
            $fotos .= "<li><a href=\"{$href}\"><img src=\"/galerie/foto{$res['ID']}_s.jpg\" alt=\"\" /></a></li>\n";
        }

        $replacements->addEncodedText(identifier: 'fotos', content: $fotos);
    }
}
