<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class svkloten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'vereine',
            2 => 'vereine300'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Schützenverein Kloten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
