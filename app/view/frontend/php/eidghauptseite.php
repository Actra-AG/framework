<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidghauptseite extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidghauptseite'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Eidgenössisches Schützenfest 2007 im Tessin';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
