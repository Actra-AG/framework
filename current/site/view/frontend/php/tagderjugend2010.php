<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class tagderjugend2010 extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'tagderjugend2010'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Tag der Jugend 2010';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
