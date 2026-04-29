<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class ktsf extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'ktsf'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Kant. Schützenfeste';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
