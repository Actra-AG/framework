<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class vorstand extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'vorstand'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Vorstand';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
