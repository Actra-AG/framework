<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class svkloten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereine300'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Schützenverein Kloten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
