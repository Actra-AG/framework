<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\backend\libs\db\DB;
use actra\yuf\core\HttpResponse;
use actra\yuf\html\HtmlDocument;
use app\libs\common\CalendarGroup;
use app\view\FrontendView;

class jp extends FrontendView
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
        $gruppen = '';
        $liste = '';
        $requestedGroup = (string)($this->getPathVar(nr: 1) ?? 'sa');
        $requestedType = (string)($this->getPathVar(nr: 2) ?? '');
        $requestedYear = (int)($this->getPathVar(nr: 3) ?? date(\"Y\"));

        $calendarGroup = CalendarGroup::get(group: $requestedGroup, public: true);
        if ($calendarGroup === null) {
            HttpResponse::redirectAndExit(location: \"jpAll.html\");
        }

        $allGroups = ['sa', 'js', 'mw', 'gm', 'vs', 'vt', 'wb'];
        $groupTitles = [
            'sa' => 'Schiessanlässe',
            'js' => 'Jungschützen / Junioren',
            'mw' => 'Matchwesen',
            'gm' => 'Gruppenmeisterschaft',
            'vs' => 'Vorstand / Delegierte',
            'vt' => 'Veteranen',
            'wb' => 'Weiterbildung'
        ];
        $typeTitles = [
            'sa300' => 'Gewehr 300m',
            'sa50' => 'Pistole 50m',
            'sa25' => 'Pistole 25m',
            'sa10' => 'Luftpistole 10m',
            'mw300' => 'Gewehr 300m',
            'mw50' => 'Gewehr 50m',
            'mwlg' => 'Luftgewehr 10m',
            'mwlp' => 'Luftpistole 10m',
            'gm300' => 'Gewehr 300m',
            'gm50' => 'Gewehr 50m',
            'gm25' => 'Pistole 25m',
            'gm10' => 'Luftpistole 10m',
            'js' => 'Jungschützen / Junioren',
            'vs' => 'Vorstand / Delegierte',
            'vt' => 'Veteranen',
            'wb' => 'Weiterbildung'
        ];

        $typ = $requestedType;
        if ($typ === '' || !in_array($typ, $calendarGroup->items)) {
            $typ = count($calendarGroup->items) > 0 ? $calendarGroup->items[0] : $requestedGroup;
        }

        $res = DB::get()->select(sql: \"SELECT DISTINCT jahr FROM jahresprogramm WHERE vorstand=0 ORDER BY jahr DESC\");
        $jahresnavi = '';
        foreach ($res as $val) {
            $jahrVal = (int)$val->jahr;
            $jahresnavi .= \"<li><a href=\\"jp-{$requestedGroup}-{$typ}-{$jahrVal}.html\\"\";
            if ($jahrVal == $requestedYear) {
                $jahresnavi .= ' class=\"active\"';
            }
            $jahresnavi .= \">{$jahrVal}</a></li>\n\";
        }

        foreach ($allGroups as $key) {
            $gruppen .= \"<li><a href=\\"jp-{$key}.html\\"\";
            if ($key == $requestedGroup) {
                $gruppen .= ' class=\"active\"';
            }
            $gruppen .= \">{$groupTitles[$key]}</a></li>\n\";
        }

        $intro = '';
        foreach ($calendarGroup->items as $val) {
            $intro .= \"<li><a href=\\"jp-{$requestedGroup}-{$val}-{$requestedYear}.html\\"\";
            if ($val == $typ) {
                $intro .= ' class=\"active\"';
            }
            $intro .= \">{$typeTitles[$val]}</a></li>\n\";
        }

        $res = DB::get()->select(
            sql: \"
SELECT
  p.ID, v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.typ=? AND p.jahr=?
  
ORDER BY
  p.datumVon, p.titel
\",
            parameters: [$typ, $requestedYear]
        );

        foreach ($res as $val) {
            $datum = ($val->datumVon == $val->datumBis) ? $val->datumVon : \"{$val->datumVon} - {$val->datumBis}\";
            $verein = ($val->verein == '') ? '&nbsp;' : $val->verein;

            $liste .= \"<tr><td>{$datum}</td><td><a href=\\"anlass-{$requestedGroup}-{$typ}-{$requestedYear}-{$val->ID}.html\\">{$val->titel}</a></td><td>{$verein}</td><td>{$val->ort}</td></tr>\n\";
        }

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $typeTitles[$typ] ?? '');
        
        $lastmodRes = DB::get()->select(sql: \"SELECT DATE_FORMAT(MAX(lastmod), '%d.%m.%Y %H:%i') AS lastmod FROM jahresprogramm\");
        $lastmod = $lastmodRes[0]->lastmod ?? '';

        $replacements->addEncodedText(identifier: 'lastmod', content: $lastmod);
        $replacements->addEncodedText(identifier: 'gruppen', content: $gruppen);
        $replacements->addEncodedText(identifier: 'jahresnavi', content: $jahresnavi);
        $replacements->addEncodedText(identifier: 'liste', content: $liste);
        $replacements->addEncodedText(identifier: 'intro', content: $intro);
    }
}
