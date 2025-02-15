<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\html;

class HtmlDataObjectCollection
{
    /** @var HtmlDataObject[] */
    private(set) array $items = [];

    public function add(HtmlDataObject $htmlDataObject): void
    {
        $this->items[] = $htmlDataObject;
    }
}