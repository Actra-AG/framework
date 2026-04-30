<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use app\libs\db\DbDocumentRepository;
use app\libs\db\DbEventRepository;
use app\view\FrontendView;

class anlass extends FrontendView
{
    public function __construct()
    {
        parent::__construct(
          maxAllowedPathVars: 4,
        );
    }

    protected function getActiveNavigationItems(): array
    {
        return [
          1 => 'jahresprogramm',
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Jahresprogramm';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $requestedGroup = $this->getPathVar(nr: 1);
        $requestedType = $this->getPathVar(nr: 2);
        $requestedYear = $this->getPathVar(nr: 3);

        if ($requestedGroup === 'all') {
            $year = $requestedYear ?? 0;
            $month = $this->getPathVar(nr: 3) ?? 0; // In oldExecute it was var 3
            $backlink = "jpAll-{$year}-{$month}.html";
            $htmlDocument->setActiveHtmlId(
              key: 2,
              val: 'jpAll'
            );
        } else {
            $backlink = "jp-{$requestedGroup}-{$requestedType}-{$requestedYear}.html";
            $htmlDocument->setActiveHtmlId(
              key: 2,
              val: $requestedGroup
            );
        }

        $ID = (int)($this->getPathVar(nr: 4) ?? 0);
        $dbEvent = DbEventRepository::selectByID(ID: $ID);
        if ($dbEvent === null) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $dbEvent->title);
        $verein = ($dbEvent->clubName == '') ? 'unbekannt' : $dbEvent->clubName;
        $datum = ($dbEvent->dateFrom == $dbEvent->dateTo) ? $dbEvent->dateFrom->format(
          format: 'd.m.Y'
        ) : $dbEvent->dateFrom->format(format: 'd.m.Y') . ' - ' . $dbEvent->dateTo->format(format: 'd.m.Y');
        $zeit = $dbEvent->timeFormatted;
        $ort = $dbEvent->location;
        $bemerkungen = ($dbEvent->notes == '') ? '' : "<dl class=\"group\"><dt>Bemerkungen:</dt><dd>" . nl2br(
            string: $dbEvent->notes
          ) . "</dd></dl>";
        $href = "/calendar/{$ID}/event.ics";
        $export = !$dbEvent->export ? '' : "<dl class=\"group\"><dt>Kalenderexport:</dt><dd><a href=\"{$href}\" title=\"In Kalender übernehmen\"><img src=\"/images/calendar_add.png\" alt=\"\" /></a></dd></dl>";

        $pdfArr = [];

        $dbQuery = DbDocumentRepository::getDbQueryForEventDocuments(eventID: $ID);
        $dbDocumentCollection = DbDocumentRepository::select(dbQuery: $dbQuery);
        foreach ($dbDocumentCollection->list() as $dbDocument) {
            $filePath = $dbDocument->getFilePath();
            if (file_exists(filename: $filePath) || true) {
                $doktitel = ($dbDocument->title == '') ? 'ohne Titel' : $dbDocument->title;
                $pdfArr[] = "<li><a href=\"" . $dbDocument->getDocumentHref() . "\">{$doktitel}</a></li>\n";
            }
        }

        if (count(value: $pdfArr) != 0) {
            $dokumente = "<dl class=\"group\"><dt>Dokument(e):</dt><dd><ul class=\"pdflink\">\n" . implode(
                "\n",
                $pdfArr
              ) . "</ul></dd></dl>";
        } else {
            $dokumente = '';
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
