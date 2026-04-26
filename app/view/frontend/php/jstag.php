<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class jstag extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'js',
            2 => 'jstag'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jungschützen/Nachwuchs Jungschützentag';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
