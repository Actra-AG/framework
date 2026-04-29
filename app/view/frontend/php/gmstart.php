<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class gmstart extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'gm',
            2 => 'gmstart'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Gruppenmeisterschaft Startlisten';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
