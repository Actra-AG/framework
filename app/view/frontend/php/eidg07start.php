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

class eidg07start extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'eidgenössisches Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $res = DB::get()->select(
            sql: \"SELECT * FROM fotos WHERE katID IN (SELECT ID FROM alben WHERE typ=2) ORDER BY RAND() LIMIT 1\"
        );
        $item = $res[0] ?? null;
        $foto = ($item) ? \"<img src=\\"/galerie/foto{$item->ID}_s.jpg\\" alt=\\"\\" />\" : '';

        $res = DB::get()->select(
            sql: \"SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE eidg=1 ORDER BY datum DESC, ID DESC LIMIT 1\"
        );
        $item = $res[0] ?? null;

        $datum = ($item) ? $item->datumD : '';
        $eidgnews = ($item) ? \"<h5>{$item->titel}</h5>\n{$item->teaser}<p><a href=\\"eidg07news.html\\">weitere Informationen</a></p>\" : 'Zurzeit keine News vorhanden';

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'foto', content: $foto);
        $replacements->addEncodedText(identifier: 'datum', content: $datum);
        $replacements->addEncodedText(identifier: 'eidgnews', content: $eidgnews);
    }
}
