<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class newsArchiv extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'start',
            2 => 'newsarchiv'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Neuigkeiten-Archiv';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }

    public function oldExecute()
    {
        $news = '';

        $cond = "n.archiv=? AND n.typ=?";
        $paramsArr[] = 1;
        $paramsArr[] = 1;

        $pos = 0;

        $fn = "newsfilter1";

        if (isset($_GET['filter']) && $_GET['filter'] == 'reset' && isset($_SESSION[$fn])) {
            unset($_SESSION[$fn]);
        }
        if (isset($_GET['pos'])) {
            $_SESSION[$fn]['pos'] = (is_numeric($_GET['pos'])) ? $_GET['pos'] : 0;
        }
        if (isset($_SESSION[$fn]['pos'])) {
            $pos = (int)$_SESSION[$fn]['pos'];
        }
        if ($pos < 0) {
            $pos = 0;
        }

        $sql = "
SELECT
  COUNT(n.ID) AS anz
  
FROM
  news n
  
WHERE
  {$cond}
";
        $qry = $this->db->prepareAndExecute($sql, $paramsArr);
        $res = $qry->fetchObject();
        if ($res->anz == 0) {
            $news = "<p>Es gibt keine archivierten Neuigkeiten.</p>";
        } else {
            $pagination = $this->showPage->getPagenavi("newsArchiv", $res->anz, $pos);
            $news .= $pagination;
            $entriesPerPage = (int)$this->showPage->config['lists']['entriesPerPage'];
            $sql = "
  SELECT
    n.ID, n.titel, n.teaser, n.text
 
  FROM
    news n
  ";
            $sql .= "WHERE
    {$cond}
  
  ORDER BY
    n.datum DESC

  LIMIT
    {$pos}, {$entriesPerPage}";

            $qry = $this->db->prepareAndExecute($sql, $paramsArr);
            while ($res = $qry->fetchObject()) {
                $news .= "<div class=\"startnews group\"><h3>{$res-> titel}</h3>{$res -> teaser}";
                if ($res->text != "") {
                    $href = "newsDetails-{$res -> ID}.html";
                    $news .= "<p><a href=\"{$href}\">weitere Informationen</a></p>";
                }
                $news .= "</div>\n";
            }
            $news .= $pagination;
        }

        $this->placeholders['news'] = $news;
    }
}
