<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class newsDetails extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'News';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $ID = (int)($this->getPathVar(nr: 1) ?? 0);

        $sql = "SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE ID=?";
        $qry = $this->db->prepareAndExecute($sql, [$ID]);
        if ($qry->rowCount() == 0) {
            $this->redirect("start.html");
            return;
        }
        $res = $qry->fetchObject();

        $news = "<h4><em>{$res->datumD}</em></h4>\n{$res->htmlContent}";
        $news .= "<p class=\"backlink\">&laquo; <a href=\"javascript:history.back();\">zurück</a></p>";

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'titel', content: $res->titel);
        $replacements->addEncodedText(identifier: 'news', content: $news);
    }
}
