<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

class DbDocumentCollection
{
    /** @var DbDocument[]
     */
    private array $items = [];

    public function add(DbDocument $dbDocument): void
    {
        $this->items[$dbDocument->ID] = $dbDocument;
    }

    /**
     * @return DbDocument[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbDocument
    {
        return current(array: $this->items);
    }
}