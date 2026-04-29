<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidg07ranglisten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07ranglisten'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Ranglisten vom Eidgenössischen Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
