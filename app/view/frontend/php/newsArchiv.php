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

class newsArchiv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'newsArchiv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'News-Archiv';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $year = (int)($this->getPathVar(nr: 1) ?? date(\"Y\"));

        $res = DB::get()->select(
            sql: \"SELECT DISTINCT YEAR(datum) AS jahr FROM news ORDER BY jahr DESC\"
        );
        
        $news = '<ul class=\"jahresnavi group\">';
        foreach ($res as $val) {
            $news .= \"<li><a href=\\"newsArchiv-{$val->jahr}.html\\"\";
            if ($val->jahr == $year) {
                $news .= ' class=\"active\"';
            }
            $news .= \">{$val->jahr}</a></li>\n\";
        }
        $news .= \"</ul>\n\";

        $res = DB::get()->select(
            sql: \"SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE YEAR(datum)=? ORDER BY datum DESC, ID DESC\",
            parameters: [$year]
        );

        foreach ($res as $val) {
            $news .= \"<div class=\\"eidgnews group\\"><h4>{$val->titel} <em>{$val->datumD}</em></h4>\n{$val->teaser}\";
            if ($val->htmlContent != '') {
                $news .= \"<p><a href=\\"newsDetails-{$val->ID}.html\\">weitere Informationen</a></p>\";
            }
            $news .= \"</div>\n\";
        }

        $htmlDocument->replacements->addEncodedText(identifier: 'news', content: $news);
    }
}
