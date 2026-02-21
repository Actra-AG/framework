<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class ekschiessen2015 extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => ''
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Einzelkonkurrenz 2015';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
