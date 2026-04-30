<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use app\libs\common\Helper;
use app\libs\db\DbEventCollection;
use app\libs\db\DbEventRepository;
use app\view\FrontendView;

class jpAll extends FrontendView
{
    private string $pageTitle;

    public function __construct()
    {
        parent::__construct(
          maxAllowedPathVars: 2,
        );
    }

    protected function getActiveNavigationItems(): array
    {
        return [
          1 => 'jahresprogramm',
          2 => 'jpAll'
        ];
    }

    protected function getPageTitle(): string
    {
        return $this->pageTitle;
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbEventCollection = DbEventRepository::select(dbQuery: DbEventRepository::getAllPublicEventsCollection());
        $availableYears = $dbEventCollection->getAvailableYears();
        $inputYear = $this->getPathVar(nr: 1);
        if ($inputYear === null) {
            $currentYear = (int)date('Y');
            $selectedYear = in_array(needle: $currentYear, haystack: $availableYears) ? $currentYear : (int)max(
              value: $availableYears
            );
        } elseif (!in_array(needle: $inputYear, haystack: $availableYears)) {
            throw new NotFoundException();
        } else {
            $selectedYear = (int)$inputYear;
        }
        $availableMonths = $dbEventCollection->getAvailableMonths(year: $selectedYear);
        $inputMonth = $this->getPathVar(nr: 2);
        if ($inputMonth === null) {
            $currentMonth = (int)date(format: 'm');
            $selectedMonth = in_array(needle: $currentMonth, haystack: $availableMonths) ? $currentMonth : (int)max(
              value: $availableMonths
            );
        } elseif (!in_array(needle: $inputMonth, haystack: $availableMonths)) {
            throw new NotFoundException();
        } else {
            $selectedMonth = (int)$inputMonth;
        }
        $years = "<div id=\"nav-content\" class=\"group\"><ul>\n";
        foreach ($availableYears as $year) {
            $href = "jpAll-{$year}.html";
            $gt = ($year == $selectedYear) ? "<strong>{$year}</strong>" : "<a href=\"{$href}\">{$year}</a>";
            $years .= "<li>{$gt}</li>\n";
        }
        $years .= "</ul>\n</div>";

        $monthArr = [
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
          12 => 'Dezember',
        ];

        $months = '<ul id="nav-year" class="group">';
        foreach ($availableMonths as $month) {
            $href = "jpAll-{$selectedYear}-{$month}.html";
            $short = substr(string: $monthArr[$month], offset: 0, length: 3);
            $item = ($month === $selectedMonth) ? '<strong>' . $short . '</strong>' : '<a href="' . $href . '">' . $short . '</a>';
            $months .= '<li>' . $item . '</li>';
        }
        $months .= '</ul>';
        $this->pageTitle = 'Jahresprogramm ' . $monthArr[$selectedMonth] . ' ' . $selectedYear;
        $list = '';

        $selectedItems = new DbEventCollection();
        foreach ($dbEventCollection->list() as $dbEvent) {
            if ($dbEvent->isMonth(year: $selectedYear, month: $selectedMonth)) {
                $selectedItems->add(dbEvent: $dbEvent);
            }
        }
        if ($selectedItems->isEmpty()) {
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
            $list .= "<table cellspacing=\"0\" class=\"normtable\">\n<thead>\n" . Helper::dynTableHeader(
                $fArr,
                $orderby,
                $ox
              ) . "</thead>\n<tbody>\n";
            $i = 0;
            foreach ($selectedItems->list() as $dbEvent) {
                $i++;
                $alt = ($i % 2 == 0) ? ' class="alt"' : '';
                $datum = ($dbEvent->dateFrom == $dbEvent->dateTo) ? $dbEvent->dateTo->format(
                  format: 'd.m.Y'
                ) : "{$dbEvent->dateFrom->format(format: 'd.m.Y')} - <br />{$dbEvent->dateTo->format(format: 'd.m.Y')}";
                $filename = 'event.ics';
                $href = "/calendar/{$dbEvent->ID}/{$filename}";
                $export = ($dbEvent->export) ? "<a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a>" : '';

                $href = "anlass-all-{$selectedYear}-{$selectedMonth}-{$dbEvent->ID}.html";
                $list .= "<tr{$alt}><td>{$datum}</td>\n<td><a href=\"{$href}\">{$dbEvent->title}</a></td>\n<td>{$dbEvent->location}</td>\n<td>{$export}</td></tr>\n";
            }
            $list .= "</tbody>\n</table>";
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
          identifier: 'lastModified',
          content: $dbEventCollection->getMaxLastModified()->format(format: 'd.m.Y H:i:s')
        );
        $replacements->addEncodedText(
          identifier: 'years',
          content: $years
        );
        $replacements->addEncodedText(
          identifier: 'months',
          content: $months
        );
        $replacements->addEncodedText(
          identifier: 'list',
          content: $list
        );
    }
}
