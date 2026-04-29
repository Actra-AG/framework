<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class ehrenmitglieder extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'ueberuns',
            2 => 'ehrenmitglieder'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Ehrenmitglieder';
    }

    public function prepareHtmlDocument(HtmlDocument ): void
    {
        $ehren = '';

        $sql = "SELECT * FROM benutzer WHERE ehren=1 ORDER BY ernannt, nachname, vorname";
        $qry = $this->db->prepareAndExecute($sql);
        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $ehren .= "<tr><td>{$res['nachname']}</td><td>{$res['vorname']}</td><td>{$res['plz']} {$res['ort']}</td><td>{$res['ernannt']}</td></tr>\n";
        }

        $htmlDocument->replacements->addEncodedText(
            identifier: 'ehren',
            content: $ehren
        );
    }
}
