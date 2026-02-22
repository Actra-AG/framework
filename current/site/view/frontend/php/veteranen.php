<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class veteranen extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'veteranen',
            2 => 'veteranenallg'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Veteranen des BSVB';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
