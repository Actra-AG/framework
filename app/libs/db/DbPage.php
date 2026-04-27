<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObject;

readonly class DbPage
{
    public function __construct(
        public int $ID,
        public string $htmlContent,
        public string $phpContent
    ) {
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'htmlContent',
            content: $this->htmlContent,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'phpContent',
            content: $this->phpContent,
            isEncodedForRendering: false
        );

        return $htmlDataObject;
    }
}