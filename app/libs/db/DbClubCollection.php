<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\form\FormOptions;
use actra\yuf\html\HtmlDataObjectCollection;
use actra\yuf\html\HtmlText;

class DbClubCollection
{
    /** @var DbClub[] */
    private array $items = [];

    public function add(DbClub $dbClub): void
    {
        $this->items[$dbClub->ID] = $dbClub;
    }

    /**
     * @return DbClub[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function getFormOptions(): FormOptions
    {
        $formOptions = new FormOptions();
        foreach ($this->items as $dbClub) {
            $formOptions->addItem(
                key: (string)$dbClub->ID,
                htmlText: HtmlText::encoded(textContent: $dbClub->name)
            );
        }

        return $formOptions;
    }

    public function render(): ?HtmlDataObjectCollection
    {
        $userGroups = new HtmlDataObjectCollection();
        foreach ($this->items as $dbClub) {
            $userGroups->add(htmlDataObject: $dbClub->render());
        }

        return $userGroups;
    }

    public function listIDs(): array
    {
        return array_keys(array: $this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbClub
    {
        return current(array: $this->items);
    }
}