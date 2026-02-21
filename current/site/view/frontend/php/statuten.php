<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class statuten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => ''
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Statuten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
