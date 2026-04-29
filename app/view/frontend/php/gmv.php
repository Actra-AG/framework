<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class gmv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'gm',
            2 => 'gmv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Gruppenmeisterschaft 2010';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
