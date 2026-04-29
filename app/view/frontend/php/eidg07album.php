<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

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
        $eidgalbum = '';

        $sql = "SELECT * FROM alben WHERE typ=2 ORDER BY titel";
        $qry = $this->db->prepareAndExecute($sql);
        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $href = "eidg07fotos-{$res['ID']}.html";
            $eidgalbum .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
        }

        $htmlDocument->replacements->addEncodedText(
            identifier: 'eidgalbum',
            content: $eidgalbum
        );
    }
}
