<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class ktsf extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'ktsf'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Kant. Schützenfeste';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
