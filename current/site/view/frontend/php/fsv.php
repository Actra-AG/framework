<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class fsv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'fs',
            2 => 'fsv'
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
