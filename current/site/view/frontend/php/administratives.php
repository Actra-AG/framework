<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class administratives extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'administratives'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Administratives';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}