<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class jpAll extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'jahresprogramm',
            2 => 'jpAll'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jahresprogramm';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {

    }

    public function oldExecute()
    {

        $sql = "SELECT DATE_FORMAT(MAX(lastmod), '%d.%m.%Y %T') AS lastmod, MIN(datumVon) AS minDate, MAX(datumBis) AS maxDate FROM jahresprogramm WHERE confirmed!='0000-00-00 00:00:00'";
        $qry = $this->db->prepareAndExecute($sql);
        $res = $qry->fetch(PDO::FETCH_ASSOC);
        $lastmod = ($res['lastmod'] != '') ? $res['lastmod'] : 'unbekannt';
        $minDate = ($res['minDate'] != '') ? $res['minDate'] : date("Y-m-d");
        $maxDate = ($res['maxDate'] != '') ? $res['maxDate'] : date("Y-m-d");

        $minArr = explode('-', $minDate);
        $maxArr = explode('-', $maxDate);

        $minYear = $minArr[0];
        $maxYear = $maxArr[0];

        $currentYear = (int)($this->showPage->arrVars[1] ?? date('Y'));
        $currentMonth = (int)($this->showPage->arrVars[2] ?? date('m'));

        if ($currentYear < $minYear) {
            $currentYear = $minYear;
        }

        if ($currentYear > $maxYear) {
            $currentYear = $maxYear;
        }

        $minMonth = (int)(($currentYear == $minYear) ? $minArr[1] : 1);
        $maxMonth = (int)(($currentYear == $maxYear) ? $maxArr[1] : 12);

        if ($currentMonth < $minMonth) {
            $currentMonth = $minMonth;
        }

        if ($currentMonth > $maxMonth) {
            $currentMonth = $maxMonth;
        }

        $years = "<div id=\"nav-content\" class=\"group\"><ul>\n";
        for ($year = $minYear; $year <= $maxYear; $year++) {
            $href = "jpAll-{$year}-{$currentMonth}.html";
            $gt = ($year == $currentYear) ? "<strong>{$year}</strong>" : "<a href=\"{$href}\">{$year}</a>";
            $years .= "<li>{$gt}</li>\n";
        }
        $years .= "</ul>\n</div>";

        $monthArr = [
            1  => 'Januar',
            2  => 'Februar',
            3  => 'März',
            4  => 'April',
            5  => 'Mai',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'August',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Dezember',
        ];

        $months = '<ul id="nav-year" class="group">';
        for ($month = $minMonth; $month <= $maxMonth; $month++) {
            $href = "jpAll-{$currentYear}-{$month}.html";
            $short = substr($monthArr[$month], 0, 3);
            $item = ($month == $currentMonth) ? '<strong>' . $short . '</strong>' : '<a href="' . $href . '">' . $short . '</a>';
            $months .= '<li>' . $item . '</li>';
        }
        $months .= '</ul>';

        $this->showPage->pageArr['platzhalter']['title'] = 'Jahresprogramm ' . $monthArr[$currentMonth] . ' ' . $currentYear;

        $list = '';

        $paramsArr[] = date('Y-m-d', mktime(0, 0, 0, $currentMonth + 1, 0, $currentYear));
        $paramsArr[] = date('Y-m-d', mktime(0, 0, 0, $currentMonth, 1, $currentYear));

        $sql = "SELECT COUNT(p.ID) AS anz FROM jahresprogramm p WHERE p.confirmed!='0000-00-00 00:00:00' AND p.datumVon<=? AND p.datumBis>=?";
        $qry = $this->db->prepareAndExecute($sql, $paramsArr);
        $res = $qry->fetchObject();
        if ($res->anz == 0) {
            $list = "<p class=\"no-entry\">Es sind keine Anlässe erfasst.</p>";
        } else {

            $fArr['p.datum']['attributes'] = '';
            $fArr['p.datum']['order'] = 0;
            $fArr['p.datum']['ox'] = '';
            $fArr['p.datum']['value'] = 'Datum';

            $fArr['p.titel']['attributes'] = '';
            $fArr['p.titel']['order'] = 0;
            $fArr['p.titel']['ox'] = '';
            $fArr['p.titel']['value'] = 'Titel';

            $fArr['p.ort']['attributes'] = '';
            $fArr['p.ort']['order'] = 0;
            $fArr['p.ort']['ox'] = '';
            $fArr['p.ort']['value'] = 'Ort';

            $fArr['p.export']['attributes'] = '';
            $fArr['p.export']['order'] = 0;
            $fArr['p.export']['ox'] = '';
            $fArr['p.export']['value'] = '&nbsp;';

            $ox = "";
            $orderby = "p.datumVon, p.datumBis, p.zeit";
            $list .= "<table cellspacing=\"0\" class=\"normtable\">\n<thead>\n" . $this->showPage->dynTableHeader($fArr, $orderby, $ox) . "</thead>\n<tbody>\n";
            $i = 0;
            $sql = "
			SELECT
				ID,
				DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon,
				DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis,
				p.titel,
				p.ort,
				p.export
      
			FROM
				jahresprogramm p
				
			WHERE
				p.confirmed!='0000-00-00 00:00:00'
				AND p.datumVon<=?
				AND p.datumBis>=?
				
			ORDER BY
				p.datumVon, p.datumBis, p.zeit
			";

            $qry = $this->db->prepareAndExecute($sql, $paramsArr);
            while ($res = $qry->fetchObject()) {
                $i++;
                $alt = ($i % 2 == 0) ? ' class="alt"' : '';
                $datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res->datumVon} - <br />{$res->datumBis}";
                $filename = 'event.ics';
                $href = "/calendar/{$res->ID}/{$filename}";
                $export = ($res->export == 1) ? "<a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a>" : '';

                $href = "anlass-all-{$currentYear}-{$currentMonth}-{$res->ID}.html";
                $list .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"{$href}\">{$res->titel}</a></td>\n<td>{$res->ort}</td>\n<td>{$export}</td></tr>\n";
            }
            $list .= "</tbody>\n</table>";
        }
        $this->placeholders['lastmod'] = $lastmod;
        $this->placeholders['years'] = $years;
        $this->placeholders['months'] = $months;
        $this->placeholders['list'] = $list;
    }
}
