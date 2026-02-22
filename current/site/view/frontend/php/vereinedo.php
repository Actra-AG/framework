<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class vereinedo extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereinedo'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Diverse Organisationen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
