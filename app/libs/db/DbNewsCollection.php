<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObjectCollection;

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

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbNews
    {
        return current(array: $this->items);
    }
}