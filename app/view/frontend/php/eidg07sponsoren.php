<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidg07sponsoren extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07sponsoren'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Sponsoren vom Eidgenössischen Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
