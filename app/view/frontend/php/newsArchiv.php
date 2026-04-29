<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

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
        $year = $this->getPathVar(nr: 1) ?? date("Y");

        $sql = "SELECT DISTINCT YEAR(datum) AS jahr FROM news ORDER BY jahr DESC";
        $qry = $this->db->prepareAndExecute($sql);
        $yearsArr = $qry->fetchAll(\PDO::FETCH_COLUMN);

        $news = '<ul class="jahresnavi group">';
        foreach ($yearsArr as $val) {
            $news .= "<li><a href=\"newsArchiv-{$val}.html\"";
            if ($val == $year) {
                $news .= ' class="active"';
            }
            $news .= ">{$val}</a></li>\n";
        }
        $news .= "</ul>\n";

        $sql = "SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE YEAR(datum)=? ORDER BY datum DESC, ID DESC";
        $paramsArr = [$year];
        $qry = $this->db->prepareAndExecute($sql, $paramsArr);

        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $news .= "<div class=\"eidgnews group\"><h4>{$res['titel']} <em>{$res['datumD']}</em></h4>\n{$res['teaser']}";
            if ($res['htmlContent'] != '') {
                $news .= "<p><a href=\"newsDetails-{$res['ID']}.html\">weitere Informationen</a></p>";
            }
            $news .= "</div>\n";
        }

        $htmlDocument->replacements->addEncodedText(identifier: 'news', content: $news);
    }
}
