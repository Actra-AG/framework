<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class sitemap extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'sitemap'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Sitemap';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
