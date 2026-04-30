<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\backend\libs\db\DB;
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

        $res = DB::get()->select(
            sql: \"SELECT titel FROM alben WHERE ID=?\",
            parameters: [$katID]
        );
        $item = $res[0] ?? null;
        
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $item ? $item->titel : '');

        $fotos = '';

        $res = DB::get()->select(
            sql: \"SELECT * FROM fotos WHERE katID=? ORDER BY ID\",
            parameters: [$katID]
        );
        foreach ($res as $val) {
            $href = \"eidg07foto-{$katID}-{$val->ID}.html\";
            $fotos .= \"<li><a href=\\"{$href}\\"><img src=\\"/galerie/foto{$val->ID}_s.jpg\\" alt=\\"\\" /></a></li>\n\";
        }

        $replacements->addEncodedText(identifier: 'fotos', content: $fotos);
    }
}
