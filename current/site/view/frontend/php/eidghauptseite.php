<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class eidghauptseite extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidghauptseite'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidgenössisches Schützenfest 2007 im Tessin';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
