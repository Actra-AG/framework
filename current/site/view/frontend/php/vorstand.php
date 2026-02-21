<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class vorstand extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'vorstand'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Vorstand';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
