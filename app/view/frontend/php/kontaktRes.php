<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\backend\libs\db\DB;
use actra\yuf\core\HttpResponse;
use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class kontaktRes extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'vorstand'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Kontakt';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $toID = (int)($this->getPathVar(nr: 1) ?? 1);

        $res = DB::get()->select(
            sql: \"SELECT vorname, nachname FROM benutzer WHERE ID=? AND kontakt=1\",
            parameters: [$toID]
        );
        if (count($res) === 0) {
            HttpResponse::redirectAndExit(location: \"vorstand.html\");
        }
        $item = $res[0];

        $htmlDocument->replacements->addEncodedText(identifier: 'title', content: \"Kontakt zu {$item->vorname} {$item->nachname}\");
    }
}
