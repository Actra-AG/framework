<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class jugends extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'jugends'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Zürcher Unterländer Jugendschiessen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
