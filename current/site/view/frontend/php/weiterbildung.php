<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class weiterbildung extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'weiterbildung'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Aus- und Weiterbildungen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
