<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\frontend\php;

use actra\backend\libs\db\DB;
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

        $res = DB::get()->select(
            sql: \"SELECT titel FROM alben WHERE ID=?\",
            parameters: [$katID]
        );
        $item = $res[0] ?? null;
        
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(identifier: 'title', content: $item ? $item->titel : '');

        $res = DB::get()->select(
            sql: \"SELECT * FROM fotos WHERE ID=?\",
            parameters: [$fotoID]
        );
        $item = $res[0] ?? null;

        $size = getimagesize($_SERVER['DOCUMENT_ROOT'] . \"/galerie/foto{$fotoID}.jpg\");

        $replacements->addEncodedText(identifier: 'katid', content: (string)$katID);
        $replacements->addEncodedText(identifier: 'fotoid', content: (string)$fotoID);
        $replacements->addEncodedText(identifier: 'imgsize', content: $size[3] ?? '');
        $replacements->addEncodedText(identifier: 'text', content: $item ? nl2br($item->text) : '');
    }
}
