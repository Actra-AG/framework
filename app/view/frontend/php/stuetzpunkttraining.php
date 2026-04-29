<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class stuetzpunkttraining extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'stuetzpunkttraining'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Stützpunkttraining ZHSV Bülach';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}
