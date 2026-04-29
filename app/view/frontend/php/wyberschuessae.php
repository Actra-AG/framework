<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class wyberschuessae extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'anlaesse',
            2 => 'wyberschuessae'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Unterländer Wyberschüssä';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
