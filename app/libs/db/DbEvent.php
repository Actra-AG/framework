<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObject;
use app\view\backend\php\member;
use DateTimeImmutable;

readonly class DbEvent
{
    public function __construct(
        public int $ID,
        public string $title,
        public DateTimeImmutable $registered,
        public DateTimeImmutable $dateFrom,
        public DateTimeImmutable $dateTo
    ) {
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'detailsHref',
            content: member::getPath(ID: $this->ID),
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'title',
            content: $this->title,
            isEncodedForRendering: false
        );
        $htmlDataObject->addTextElement(
            propertyName: 'registered',
            content: $this->registered->format(format: 'd.m.Y H:i:s'),
            isEncodedForRendering: false
        );

        return $htmlDataObject;
    }
}