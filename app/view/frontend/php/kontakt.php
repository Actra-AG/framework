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
use classes\FormMailer;

class kontakt extends FrontendView
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
            sql: \"SELECT vorname, nachname, email FROM benutzer WHERE ID=? AND kontakt=1\",
            parameters: [$toID]
        );
        if (count($res) === 0) {
            HttpResponse::redirectAndExit(location: \"vorstand.html\");
        }
        $item = $res[0];

        $status = '';
        if (isset($_GET['send'])) {
            $status = (new FormMailer())->sendContactForm($item->email, \"{$item->vorname} {$item->nachname}\");
            if ($status === true) {
                HttpResponse::redirectAndExit(location: \"kontaktRes-{$toID}.html\");
            }
        }

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: \"Kontakt zu {$item->vorname} {$item->nachname}\");
        $replacements->addEncodedText(identifier: 'status', content: (string)$status);
        $replacements->addEncodedText(identifier: 'toid', content: (string)$toID);

        $replacements->addEncodedText(identifier: 'firma', content: $_POST['firma'] ?? '');
        $replacements->addEncodedText(identifier: 'vorname', content: $_POST['vorname'] ?? '');
        $replacements->addEncodedText(identifier: 'nachname', content: $_POST['nachname'] ?? '');
        $replacements->addEncodedText(identifier: 'email', content: $_POST['email'] ?? '');
        $replacements->addEncodedText(identifier: 'telefon', content: $_POST['telefon'] ?? '');
        $replacements->addEncodedText(identifier: 'betreff', content: $_POST['betreff'] ?? '');
        $replacements->addEncodedText(identifier: 'mitteilung', content: $_POST['mitteilung'] ?? '');
    }
}
