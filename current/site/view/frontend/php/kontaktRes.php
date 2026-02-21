<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class kontaktRes extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'kontakt'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Kontakt';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $toID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

        $this->showPage->pageArr['platzhalter']['title'] = 'BSVB kontaktieren';

        $sql = "
SELECT
  vorname, nachname, email
  
FROM
  benutzer
  
WHERE
  ID=?
";
        $qry = $this->db->prepareAndExecute($sql, [$toID]);
        if ($qry->rowCount() == 1) {
            $res = $qry->fetch(PDO::FETCH_ASSOC);
            $this->showPage->pageArr['platzhalter']['title'] = "{$res['vorname']} {$res['nachname']} kontaktieren";
        }
    }
}
