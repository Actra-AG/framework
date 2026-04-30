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

        $res = DB::get()->select(
            sql: \"SELECT * FROM alben WHERE typ=2 ORDER BY titel\"
        );
        foreach ($res as $val) {
            $href = \"eidg07fotos-{$val->ID}.html\";
            $eidgalbum .= \"<li><a href=\\"{$href}\\">{$val->titel}</a></li>\n\";
        }

        $htmlDocument->replacements->addEncodedText(
            identifier: 'eidgalbum',
            content: $eidgalbum
        );
    }
}
