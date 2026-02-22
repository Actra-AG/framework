<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class mwinfos extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'mwprog',
            2 => 'mwinfos'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Matchwesen Information';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
