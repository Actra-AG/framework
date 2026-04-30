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

class ehrenmitglieder extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'ehrenmitglieder'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Ehrenmitglieder';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $ehren = '';

        $res = DB::get()->select(
            sql: "SELECT * FROM benutzer WHERE ehren=1 ORDER BY ernannt, nachname, vorname"
        );
        foreach ($res as $val) {
            $ehren .= \"<tr><td>{$val->nachname}</td><td>{$val->vorname}</td><td>{$val->plz} {$val->ort}</td><td>{$val->ernannt}</td></tr>\n\";
        }

        $htmlDocument->replacements->addEncodedText(
            identifier: 'ehren',
            content: $ehren
        );
    }
}
