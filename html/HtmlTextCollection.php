<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

class HtmlTextCollection
{
    /** @var HtmlText[] */
    private array $items = [];

    /**
     * @param HtmlText[] $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add(htmlText: $item);
        }
    }

    public function add(HtmlText $htmlText): void
    {
        $this->items[] = $htmlText;
    }

    /**
     * @return HtmlText[]
     */
    public function getItems(): array
    {
        return $this->items;
    }
}