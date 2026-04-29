<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

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

        $sql = "SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE eidg=1 ORDER BY datum DESC, ID DESC";
        $qry = $this->db->prepareAndExecute($sql);
        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $eidgnews .= "<div class=\"eidgnews group\"><h4>{$res['titel']} <em>{$res['datumD']}</em></h4>\n{$res['teaser']}";
            if ($res['htmlContent'] != '') {
                $eidgnews .= "<p><a href=\"newsDetails-{$res['ID']}.html\">weitere Informationen</a></p>";
            }
            $eidgnews .= "</div>\n";
        }

        $htmlDocument->replacements->addEncodedText(identifier: 'eidgnews', content: $eidgnews);
    }
}
