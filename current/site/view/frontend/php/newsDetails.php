<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use site\libs\db\DbNewsRepository;
use site\view\FrontendView;

class newsDetails extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Startseite';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {
        $ID = (isset($this->showPage->arrVars[1])) ? $this->showPage->arrVars[1] : 0;

        $sql = "SELECT titel, teaser, text FROM news WHERE ID='{$ID}'";
        $qry = $this->db->prepareAndExecute($sql);
        if ($qry->rowCount() != 1) {
            $this->showPage->redirect("newsArchiv.html");
        }
        $res = $qry->fetch(PDO::FETCH_ASSOC);

        $news = "<div class=\"startnews group\"><h3>{$res['titel']}</h3>{$res['teaser']}{$res['text']}</div>\n";

        $titel = $res['titel'];
        $this->showPage->pageArr['platzhalter']['title'] = $titel;
        $this->showPage->pageArr['grundkonf']['navigator']['title'] = $titel;

        $this->placeholders['titel'] = $titel;
        $this->placeholders['news'] = $news;
    }

    public static function getPath(int $ID): string
    {
        return 'newsDetails-' . $ID . '.html';
    }
}