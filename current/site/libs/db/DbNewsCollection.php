<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\db;

use framework\html\HtmlDataObjectCollection;

class DbNewsCollection
{
    /** @var DbNews[] */
    private array $items = [];

    public function add(DbNews $dbNews): void
    {
        $this->items[$dbNews->ID] = $dbNews;
    }

    /**
     * @return DbNews[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function render(): HtmlDataObjectCollection
    {
        $htmlDataObjectCollection = new HtmlDataObjectCollection();
        foreach ($this->items as $dbNewsItem) {
            $htmlDataObjectCollection->add(htmlDataObject: $dbNewsItem->render());
        }
        return $htmlDataObjectCollection;
    }
}