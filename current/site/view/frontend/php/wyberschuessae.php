<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class wyberschuessae extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'wyberschuessae'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Unterländer Wyberschüssä';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
