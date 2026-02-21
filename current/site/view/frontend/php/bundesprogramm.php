<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class bundesprogramm extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'bundesprogramm',
            2 => 'bpdaten'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Bundesprogramm-Daten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
