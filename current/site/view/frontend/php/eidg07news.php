<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class eidg07news extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07news'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'News-Ticker zum eidgenössischen Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $sql = "SELECT n.ID, n.titel, n.teaser, n.text, DATE_FORMAT(n.datum, '%d.%m.%Y') AS datum, DATE_FORMAT(n.datum, '%T') AS zeit FROM news n WHERE n.archiv='0' AND n.typ=2 ORDER BY n.datum DESC";
        $qry = $this->db->prepareAndExecute($sql);
        if ($qry->rowCount() == 0) {
            $eidgnews = '<p class="noentry">keine Neuigkeiten</p>';

        } else {
            $eidgnews = '';
            $lastday = '';
            while ($res = $qry->fetch(PDO::FETCH_ASSOC)) {
                if ($res['datum'] != $lastday) {
                    if ($lastday != '') {
                        $eidgnews .= '</ul>';
                    }
                    $eidgnews .= '<h3>' . $res['datum'] . '</h3><ul>';
                }
                $href = 'newsDetails-' . $res['ID'] . '.html';
                $eidgnews .= '<li><span><a href="'.$href.'">' . $res['titel'] . '</a></span> <em>' . $res['zeit'] . '</em></li>';
                $lastday = $res['datum'];
            }
            $eidgnews .= '</ul>';
        }

        $this->placeholders['eidgnews'] = $eidgnews;
    }
}
