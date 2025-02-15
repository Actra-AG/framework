<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

use stdClass;

class HtmlDataObject
{
    private(set) stdClass $data;

    public function __construct()
    {
        $this->data = new stdClass();
    }

    public function addTextElement(string $propertyName, ?string $content, bool $isEncodedForRendering): void
    {
        if (is_null($content)) {
            $this->data->{$propertyName} = null;

            return;
        }

        $this->data->{$propertyName} = $isEncodedForRendering ? $content : HtmlEncoder::encode(value: $content);
    }

    public function addDataObject(string $propertyName, ?HtmlDataObject $htmlDataObject): void
    {
        $this->data->{$propertyName} = is_null($htmlDataObject) ? null : $htmlDataObject->data;
    }

    /**
     * @param string $propertyName
     * @param HtmlDataObject[]|null $htmlDataObjectsArray
     */
    public function addHtmlDataObjectsArray(string $propertyName, ?array $htmlDataObjectsArray): void
    {
        if (is_null($htmlDataObjectsArray)) {
            $this->data->{$propertyName} = null;

            return;
        }

        $array = [];
        foreach ($htmlDataObjectsArray as $htmlDataObject) {
            $array[] = $htmlDataObject->data;
        }

        $this->data->{$propertyName} = $array;
    }

    public function addBooleanValue(string $propertyName, bool $booleanValue): void
    {
        $this->data->{$propertyName} = $booleanValue;
    }

    public function addNullValue(string $propertyName): void
    {
        $this->data->{$propertyName} = null;
    }
}