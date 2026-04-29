<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class fsv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'fs',
            2 => 'fsv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidg. Feldschiessen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
