<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObjectCollection;

class DbMemberCollection
{
    /** @var DbMember[] */
    private array $items = [];

    public function add(DbMember $dbMember): void
    {
        $this->items[$dbMember->dbAuthUser->ID] = $dbMember;
    }

    /**
     * @return DbMember[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function render(): HtmlDataObjectCollection
    {
        $htmlDataObjectCollection = new HtmlDataObjectCollection();
        foreach ($this->items as $dbMember) {
            $htmlDataObjectCollection->add(
                htmlDataObject: $dbMember->render()
            );
        }
        return $htmlDataObjectCollection;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbMember
    {
        return current(array: $this->items);
    }
}