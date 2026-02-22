<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class bezirksm extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'bezirksm'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Bezirksmeisterschaft';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
