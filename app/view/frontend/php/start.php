<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\libs\db\DbNewsRepository;
use app\view\FrontendView;

class start extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Startseite';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $htmlDocument->replacements->addHtmlDataObjectCollection(
            identifier: 'news',
            htmlDataObjectCollection: DbNewsRepository::listForStartPage()->render()
        );
    }
}