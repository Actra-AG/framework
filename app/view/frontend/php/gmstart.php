<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class gmstart extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'gm',
            2 => 'gmstart'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Gruppenmeisterschaft Startlisten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
