<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class mwranglisten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'mw',
            2 => 'mwranglisten'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Matchwesen Ranglisten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
