<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class psbuelach extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereine25'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Pistolen - Schützen Bülach';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
