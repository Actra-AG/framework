<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class anlass extends FrontendView
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

        $dokumente = '';

        if ($this->getPathVar(nr: 1) == 'all') {
            $year = $this->getPathVar(nr: 2) ?? 0;
            $month = $this->getPathVar(nr: 3) ?? 0;

            $backlink = "jpAll-{$year}-{$month}.html";
        } else {
            $jpArr = $bsvb->getJahresprogramm();

            $gruppe = ($this->getPathVar(nr: 1) !== null && array_key_exists(
                    (string)$this->getPathVar(nr: 1),
                    $jpArr['gruppen']
                )) ? (string)$this->getPathVar(nr: 1) : 'sa';

            if (count($jpArr['gruppen'][$gruppe]) == 0) {
                $typ = $gruppe;
            } else {
                $typ = ($this->getPathVar(nr: 2) !== null && in_array(
                        $this->getPathVar(nr: 2),
                        $jpArr['gruppen'][$gruppe]
                    )) ? (string)$this->getPathVar(nr: 2) : (string)current($jpArr['gruppen'][$gruppe]);
            }

            $jahr = $this->getPathVar(nr: 3) ?? date("Y");

            $backlink = "jp-{$gruppe}-{$typ}-{$jahr}.html";
        }
        $ID = (int)($this->getPathVar(nr: 4) ?? 0);

        $sql = "
SELECT
  v.name AS verein, DATE_FORMAT(p.datumVon, '%d.%m.%Y') AS datumVon, DATE_FORMAT(p.datumBis, '%d.%m.%Y') AS datumBis, p.titel, p.ort, p.zeit, p.bemerkungen, p.export, p.vorstand
  
FROM
  jahresprogramm p
  LEFT JOIN vereine v ON p.vereinID=v.ID
  
WHERE
  p.ID=?
";
        $qry = $this->db->prepareAndExecute($sql, [$ID]);
        if ($qry->rowCount() == 0) {
            $this->redirect("jp.html");
            return;
        }
        $res = $qry->fetchObject();

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $res->titel);
        $verein = ($res->verein == '') ? 'unbekannt' : $res->verein;
        $datum = ($res->datumVon == $res->datumBis) ? $res->datumVon : "{$res->datumVon} - {$res->datumBis}";
        $zeit = $res->zeit;
        $ort = $res->ort;
        $bemerkungen = ($res->bemerkungen == '' || $res->vorstand == 1) ? '' : "<dl class="group"><dt>Bemerkungen:</dt><dd>" . nl2br(
                $res->bemerkungen
            ) . "</dd></dl>";
        $href = "/calendar/{$ID}/event.ics";
        $export = ($res->export == 0) ? '' : "<dl class="group"><dt>Kalenderexport:</dt><dd><a href="{$href}" title="In Kalender übernehmen"><img src="/images/calendar_add.png" alt="" /></a></dd></dl>";

        $pdfArr = [];

        if ($res->vorstand != 1) {
            $sql = "
		SELECT
  			d.ID, d.dateiname, d.titel, f.extension
        
		FROM
  			dokumente d
  			INNER JOIN dateiformate f ON d.type=f.mimetype
        
		WHERE
  			d.objekt='anlass' AND d.objektID=?
      
		ORDER BY
  			titel
  		";
            $qry = $this->db->prepareAndExecute($sql, [$ID]);
            while ($res = $qry->fetch(\PDO::FETCH_ASSOC)) {
                if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/dokumente/' . $res['ID'] . '.' . $res['extension'])) {
                    $key = md5("aasmdsjtk{$res['ID']}asujdt3?nz34g");

                    $doktitel = ($res['titel'] == '') ? 'ohne Titel' : $res['titel'];
                    $pdfArr[] = "<li><a href="/dokumente/{$res['ID']}/{$key}/" . urlencode(
                            $res['dateiname']
                        ) . "">{$doktitel}</a></li>\n";
                }
            }

            if (count($pdfArr) != 0) {
                $dokumente = "<dl class="group"><dt>Dokument(e):</dt><dd><ul class="pdflink">\n" . implode(
                        "\n",
                        $pdfArr
                    ) . "</ul></dd></dl>";
            }
        }

        $replacements->addEncodedText(identifier: 'backlink', content: $backlink);
        $replacements->addEncodedText(identifier: 'verein', content: $verein);
        $replacements->addEncodedText(identifier: 'datum', content: $datum);
        $replacements->addEncodedText(identifier: 'zeit', content: $zeit);
        $replacements->addEncodedText(identifier: 'ort', content: $ort);
        $replacements->addEncodedText(identifier: 'bemerkungen', content: $bemerkungen);
        $replacements->addEncodedText(identifier: 'dokumente', content: $dokumente);
        $replacements->addEncodedText(identifier: 'export', content: $export);
    }
}
