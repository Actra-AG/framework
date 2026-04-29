<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class bezirkss extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'bezirkss'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Bezirksschiessen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
