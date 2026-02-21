<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class alben extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start',
            2 => 'fotogalerie'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Fotogalerie';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $sql = "SELECT ID, titel FROM alben WHERE typ=1 ORDER BY pos";
        $qry = $this->db->prepareAndExecute($sql);
        if ($qry->rowCount() == 0) {
            $alben = "<p>Es gibt zurzeit keine Alben.</p>";

        } else {
            $alben = "<ul class=\"normliste\">\n";
            while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
                $href = "fotos-{$res['ID']}.html";
                $alben .= "<li><a href=\"{$href}\">{$res['titel']}</a></li>\n";
            }
            $alben .= "</ul>\n";

        }

        $this->placeholders['alben'] = $alben;
    }
}
