<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

class HtmlDataObjectCollection
{
    /** @var HtmlDataObject[] */
    private array $items = [];

    public function add(HtmlDataObject $htmlDataObject): void
    {
        $this->items[] = $htmlDataObject;
    }

    /**
     * @return HtmlDataObject[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}