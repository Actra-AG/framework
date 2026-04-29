<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

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

        $sql = "SELECT vorname, nachname FROM benutzer WHERE ID=? AND kontakt=1";
        $qry = $this->db->prepareAndExecute($sql, [$toID]);
        if ($qry->rowCount() == 0) {
            $this->redirect("vorstand.html");
            return;
        }
        $res = $qry->fetchObject();

        $htmlDocument->replacements->addEncodedText(identifier: 'title', content: "Kontakt zu {$res->vorname} {$res->nachname}");
    }
}
