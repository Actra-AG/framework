<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use app\libs\db\DbNewsRepository;
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
        $inputID = $this->getPathVar(nr: 1);
        if ($inputID === null) {
            throw new NotFoundException();
        }
        $dbNews = DbNewsRepository::selectByID(ID: (int)$inputID);
        if ($dbNews === null) {
            throw new NotFoundException();
        }

        $news = '<h4><em>' . $dbNews->date->format(format: 'd.m.Y') . '</em></h4>' . $dbNews->htmlContent;
        $news .= '<p class="backlink">&laquo; <a href="javascript:history.back();">zurück</a></p>';
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'titel', content: $dbNews->title);
        $replacements->addEncodedText(identifier: 'news', content: $news);
    }

    public static function getPath(int $ID): string
    {
        return 'newsDetails-' . $ID . '.html';
    }
}
