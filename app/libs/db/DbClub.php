<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObject;

readonly class DbClub
{
    public function __construct(
        public int $ID,
        public string $name
    ) {
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'name',
            content: $this->name,
            isEncodedForRendering: true
        );
        return $htmlDataObject;
    }
}