<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\libs\db\DbNewsRepository;
use site\view\FrontendView;

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