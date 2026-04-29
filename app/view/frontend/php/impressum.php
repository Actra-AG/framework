<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class impressum extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'impressum'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Impressum';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
