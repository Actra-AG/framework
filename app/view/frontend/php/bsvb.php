<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class bsvb extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'bsvb'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Über uns';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
