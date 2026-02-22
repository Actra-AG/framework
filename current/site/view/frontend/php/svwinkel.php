<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class svwinkel extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => ''
        ];
    }

    protected function getPageTitle(): string
    {
        return 'SV Winkel-Rüti';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
