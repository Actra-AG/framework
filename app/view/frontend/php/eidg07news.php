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

class eidg07news extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07news'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'News zum eidgenössischen Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $eidgnews = '';

        $res = DB::get()->select(
            sql: \"SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE eidg=1 ORDER BY datum DESC, ID DESC\"
        );
        foreach ($res as $val) {
            $eidgnews .= \"<div class=\\"eidgnews group\\"><h4>{$val->titel} <em>{$val->datumD}</em></h4>\n{$val->teaser}\";
            if ($val->htmlContent != '') {
                $eidgnews .= \"<p><a href=\\"newsDetails-{$val->ID}.html\\">weitere Informationen</a></p>\";
            }
            $eidgnews .= \"</div>\n\";
        }

        $htmlDocument->replacements->addEncodedText(identifier: 'eidgnews', content: $eidgnews);
    }
}
