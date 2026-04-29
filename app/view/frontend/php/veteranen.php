<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class veteranen extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'veteranen',
            2 => 'veteranenallg'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Veteranen des BSVB';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
