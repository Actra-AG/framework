<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class links extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'links'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Link';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
