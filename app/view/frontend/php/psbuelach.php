<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class psbuelach extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereine25'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Pistolen - Schützen Bülach';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
