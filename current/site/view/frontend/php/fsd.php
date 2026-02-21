<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class fsd extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'fs',
            2 => 'fsd'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidg. Feldschiessen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
