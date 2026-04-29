<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class tagderjugend2010 extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'tagderjugend2010'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Tag der Jugend 2010';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
