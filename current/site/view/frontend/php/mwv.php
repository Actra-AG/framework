<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class mwv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'mw',
            2 => 'mwv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Matchwesen Vorwort';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
