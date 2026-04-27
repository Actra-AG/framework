<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

class DbPageCollection
{
    /** @var DbPage[] */
    private array $items = [];

    public function add(DbPage $dbPage): void
    {
        $this->items[$dbPage->ID] = $dbPage;
    }

    /**
     * @return DbPage[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbPage
    {
        return current(array: $this->items);
    }
}