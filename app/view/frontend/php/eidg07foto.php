<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\yuf\html\HtmlDocument;
use app\view\FrontendView;

class eidg07foto extends FrontendView
{
    protected function getActiveNavigationItems(): array
    {
        return [
            1 => 'eidg07',
            2 => 'eidg07fotos'
        ];
    }

    protected function getPageTitle(): string
    {
        return 'Foto';
    }

    public function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $katID = (int)($this->getPathVar(nr: 1) ?? 0);
        $fotoID = (int)($this->getPathVar(nr: 2) ?? 0);

        $sql = "SELECT titel FROM alben WHERE ID=?";
        $qry = $this->db->prepareAndExecute($sql, [$katID]);
        $res = $qry->fetchObject();
        
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $res->titel);

        $sql = "SELECT * FROM fotos WHERE ID=?";
        $qry = $this->db->prepareAndExecute($sql, [$fotoID]);
        $res = $qry->fetchObject();

        $size = getimagesize($_SERVER['DOCUMENT_ROOT'] . "/galerie/foto{$fotoID}.jpg");

        $replacements->addEncodedText(identifier: 'katid', content: (string)$katID);
        $replacements->addEncodedText(identifier: 'fotoid', content: (string)$fotoID);
        $replacements->addEncodedText(identifier: 'imgsize', content: $size[3]);
        $replacements->addEncodedText(identifier: 'text', content: nl2br($res->text));
    }
}
