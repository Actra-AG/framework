<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidg07start extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07start'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'eidgenössisches Schützenfest 2007';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $sql = "SELECT * FROM fotos WHERE katID IN (SELECT ID FROM alben WHERE typ=2) ORDER BY RAND() LIMIT 1";
        $qry = $this->db->prepareAndExecute($sql);
        $res = $qry->fetchObject();
        $foto = ($res) ? "<img src=\"/galerie/foto{$res->ID}_s.jpg\" alt=\"\" />" : '';

        $sql = "SELECT *, DATE_FORMAT(datum, '%d.%m.%Y') AS datumD FROM news WHERE eidg=1 ORDER BY datum DESC, ID DESC LIMIT 1";
        $qry = $this->db->prepareAndExecute($sql);
        $res = $qry->fetchObject();

        $datum = ($res) ? $res->datumD : '';
        $eidgnews = ($res) ? "<h5>{$res->titel}</h5>\n{$res->teaser}<p><a href=\"eidg07news.html\">weitere Informationen</a></p>" : 'Zurzeit keine News vorhanden';

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'foto', content: $foto);
        $replacements->addEncodedText(identifier: 'datum', content: $datum);
        $replacements->addEncodedText(identifier: 'eidgnews', content: $eidgnews);
    }
}
