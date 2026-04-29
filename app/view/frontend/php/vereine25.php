<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class vereine25 extends FrontendView
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
        return '25/50m Sektionen';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
