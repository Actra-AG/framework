<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\view\FrontendView;

class gmrangzhsv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'gm',
            2 => 'gmrang'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Gruppenmeisterschaft Ranglisten ZHSV';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }
}
