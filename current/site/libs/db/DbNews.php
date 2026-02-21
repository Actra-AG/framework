<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\db;


use DateTimeImmutable;
use framework\html\HtmlDataObject;
use site\view\frontend\php\newsDetails;

readonly class DbNews
{
    public function __construct(
        public int               $ID,
        public int               $registeredByUserID,
        public DateTimeImmutable $date,
        public string            $title,
        public string            $teaser,
        public string            $htmlContent,
        public bool              $isArchive,
        public int               $type
    )
    {
    }

    public function render(): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'title',
            content: $this->title,
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'teaser',
            content: $this->teaser,
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'htmlContent',
            content: $this->htmlContent,
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'href',
            content: newsDetails::getPath(ID: $this->ID),
            isEncodedForRendering: true
        );
        return $htmlDataObject;
    }
}