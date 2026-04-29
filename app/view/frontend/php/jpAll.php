<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class jpAll extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'jahresprogramm'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jahresprogramm';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $bsvb = new bsvb();

        $year = (int)($this->getPathVar(nr: 1) ?? 0);
        $month = (int)($this->getPathVar(nr: 2) ?? 0);

        $sql = "SELECT DISTINCT jahr FROM jahresprogramm WHERE vorstand=0 ORDER BY jahr DESC";
        $qry = $this->db->prepareAndExecute($sql);
        $yearsArr = $qry->fetchAll(\PDO::FETCH_COLUMN);

        if ($year == 0) {
            $year = (int)$yearsArr[0];
        }

        $years = '';
        foreach ($yearsArr as $val) {
            $years .= "<li><a href=\"jpAll-{$val}.html\"";
            if ($val == $year) {
                $years .= ' class="active"';
            }
            $years .= ">{$val}</a></li>\n";
        }

        $monthsArr = [
            1 => 'Januar',
            2 => 'Februar',
            3 => 'März',
            4 => 'April',
            5 => 'Mai',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'August',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Dezember'
        ];

        $months = "<li><a href=\"jpAll-{$year}-0.html\"";
        if ($month == 0) {
            $months .= ' class="active"';
        }
        $months .= ">Alle</a></li>\n";

        foreach ($monthsArr as $key => $val) {
            $months .= "<li><a href=\"jpAll-{$year}-{$key}.html\"";
            if ($key == $month) {
                $months .= ' class="active"';
            }
            $months .= ">{$val}</a></li>\n";
        }

        $sql = "
SELECT
  p.ID, v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.typ
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.jahr=? AND p.vorstand=0
";
        $paramsArr = [$year];
        if ($month != 0) {
            $sql .= " AND (MONTH(p.datumVon)=? OR MONTH(p.datumBis)=?)";
            $paramsArr[] = $month;
            $paramsArr[] = $month;
        }

        $sql .= " ORDER BY p.datumVon, p.titel";

        $qry = $this->db->prepareAndExecute($sql, $paramsArr);

        $list = '';
        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $datum = ($res['datumVon'] == $res['datumBis']) ? $res['datumVon'] : "{$res['datumVon']} - {$res['datumBis']}";
            $verein = ($res['verein'] == '') ? '&nbsp;' : $res['verein'];

            $list .= "<tr><td>{$datum}</td><td><a href=\"anlass-all-{$year}-{$month}-{$res['ID']}.html\">{$res['titel']}</a></td><td>{$verein}</td><td>{$res['ort']}</td></tr>\n";
        }

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: "Jahresprogramm {$year}");
        $replacements->addEncodedText(identifier: 'lastmod', content: (string)$bsvb->getJahresprogrammLastmod());
        $replacements->addEncodedText(identifier: 'years', content: $years);
        $replacements->addEncodedText(identifier: 'months', content: $months);
        $replacements->addEncodedText(identifier: 'list', content: $list);
    }
}
