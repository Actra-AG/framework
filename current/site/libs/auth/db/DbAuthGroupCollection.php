<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use framework\form\FormOptions;
use framework\html\HtmlText;

class DbAuthGroupCollection
{
    /** @var DbAuthGroupItem[] $items */
    private(set) array $items = [];

    public function __construct()
    {
    }

    public function add(DbAuthGroupItem $dbAuthGroupItem): void
    {
        $this->items[$dbAuthGroupItem->ID] = $dbAuthGroupItem;
    }

    public function getFormOptions(): FormOptions
    {
        $formOptions = new FormOptions();
        foreach ($this->items as $dbAuthGroupItem) {
            $formOptions->addItem(
                key: $dbAuthGroupItem->ID,
                htmlText: HtmlText::encoded(textContent: $dbAuthGroupItem->title)
            );
        }

        return $formOptions;
    }

    public function listIDs(): array
    {
        return array_keys(array: $this->items);
    }
}