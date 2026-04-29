<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class sav extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'sav'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Schiessanlässe';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
