<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class vereinejs extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereinejs'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jungschützenkurse';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
