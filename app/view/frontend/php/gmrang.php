<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class gmrang extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'gm',
            2 => 'gmrang'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Gruppenmeisterschaft Ranglisten BSVB';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
