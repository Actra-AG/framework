<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class mwranglisten extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'mw',
            2 => 'mwranglisten'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Matchwesen Ranglisten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
