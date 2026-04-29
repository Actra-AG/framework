<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
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
        $bsvb = new bsvb();

        $gruppen = '';
        $liste = '';

        $jpArr = $bsvb->getJahresprogramm();

        $gruppe = ($this->getPathVar(nr: 1) !== null && array_key_exists(
                (string)$this->getPathVar(nr: 1),
                $jpArr['gruppen']
            ) && $this->getPathVar(nr: 1) != 'vorstand') ? (string)$this->getPathVar(nr: 1) : '';
        if (!isset($jpArr['gruppen'][$gruppe])) {
            $this->redirect("jpAll.html");
            return;
        }

        if (count($jpArr['gruppen'][$gruppe]) == 0) {
            $typ = $gruppe;
        } else {
            $typ = ($this->getPathVar(nr: 2) !== null && in_array(
                    $this->getPathVar(nr: 2),
                    $jpArr['gruppen'][$gruppe]
                )) ? (string)$this->getPathVar(nr: 2) : (string)current($jpArr['gruppen'][$gruppe]);
        }

        $jahr = $this->getPathVar(nr: 3) ?? date("Y");

        $jahresnavi = '';
        foreach ($jpArr['jahre'] as $val) {
            $jahresnavi .= "<li><a href=\"jp-{$gruppe}-{$typ}-{$val}.html\"";
            if ($val == $jahr) {
                $jahresnavi .= ' class="active"';
            }
            $jahresnavi .= ">{$val}</a></li>\n";
        }

        $intro = '';
        foreach ($jpArr['gruppen'] as $key => $val) {
            if ($key != 'vorstand') {
                $gruppen .= "<li><a href=\"jp-{$key}.html\"";
                if ($key == $gruppe) {
                    $gruppen .= ' class="active"';
                }
                $gruppen .= ">{$jpArr['gruppen_titel'][$key]}</a></li>\n";
            }
        }

        foreach ($jpArr['gruppen'][$gruppe] as $val) {
            $intro .= "<li><a href=\"jp-{$gruppe}-{$val}-{$jahr}.html\"";
            if ($val == $typ) {
                $intro .= ' class="active"';
            }
            $intro .= ">{$jpArr['typen_titel'][$val]}</a></li>\n";
        }

        $sql = "
SELECT
  p.ID, v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.vorstand
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.typ=? AND p.jahr=?
  
ORDER BY
  p.datumVon, p.titel
";
        $paramsArr = [$typ, $jahr];
        $qry = $this->db->prepareAndExecute($sql, $paramsArr);

        while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
            $datum = ($res['datumVon'] == $res['datumBis']) ? $res['datumVon'] : "{$res['datumVon']} - {$res['datumBis']}";
            $verein = ($res['verein'] == '') ? '&nbsp;' : $res['verein'];

            $liste .= "<tr><td>{$datum}</td><td><a href=\"anlass-{$gruppe}-{$typ}-{$jahr}-{$res['ID']}.html\">{$res['titel']}</a></td><td>{$verein}</td><td>{$res['ort']}</td></tr>\n";
        }

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $jpArr['typen_titel'][$typ]);
        $replacements->addEncodedText(identifier: 'lastmod', content: $jpArr['lastmod']);
        $replacements->addEncodedText(identifier: 'gruppen', content: $gruppen);
        $replacements->addEncodedText(identifier: 'jahresnavi', content: $jahresnavi);
        $replacements->addEncodedText(identifier: 'liste', content: $liste);
        $replacements->addEncodedText(identifier: 'intro', content: $intro);
    }
}
