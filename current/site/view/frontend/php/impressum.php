<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class impressum extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'impressum'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Impressum';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
