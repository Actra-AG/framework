<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use framework\form\FormOptions;
use framework\html\HtmlText;

class DbAuthUserCollection
{
    /** @var DbAuthUserItem[] $items */
    private(set) array $items = [];

    public function __construct()
    {
    }

    public function add(DbAuthUserItem $dbAuthUserItem): void
    {
        $this->items[$dbAuthUserItem->ID] = $dbAuthUserItem;
    }

    public function getFormOptions(): FormOptions
    {
        $formOptions = new FormOptions();
        foreach ($this->items as $dbAuthUserItem) {
            $formOptions->addItem(
                key: $dbAuthUserItem->ID,
                htmlText: HtmlText::encoded(
                    textContent: $dbAuthUserItem->firstName . ' ' . $dbAuthUserItem->lastName . ' (' . $dbAuthUserItem->renderActive(
                    ) . ')'
                )
            );
        }

        return $formOptions;
    }

    public function isEmpty(): bool
    {
        return count(value: $this->items) === 0;
    }

    public function listIDs(): array
    {
        return array_keys(array: $this->items);
    }
}