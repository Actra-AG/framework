<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

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

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $ehren = '';

        $sql = "SELECT * FROM benutzer WHERE ehren=1 ORDER BY ernannt, nachname, vorname";
        $qry = $this->db->prepareAndExecute($sql);
        while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
            $ehren .= "<tr><td>{$res['nachname']}</td><td>{$res['vorname']}</td><td>{$res['plz']} {$res['ort']}</td><td>{$res['ernannt']}</td></tr>\n";
        }

        $this->placeholders['ehren'] = $ehren;
    }
}
