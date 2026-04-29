<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class mwinfos extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'mwprog',
            2 => 'mwinfos'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Matchwesen Information';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
