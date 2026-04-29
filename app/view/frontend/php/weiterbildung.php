<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class weiterbildung extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'weiterbildung'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Aus- und Weiterbildungen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
