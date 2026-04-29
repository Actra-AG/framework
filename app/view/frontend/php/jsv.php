<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class jsv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'js',
            2 => 'jsv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jungschützen/Nachwuchs Vorwort';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
