<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObjectCollection;

class DbEventCollection
{
    /** @var DbEvent[] */
    private array $items = [];

    public function add(DbEvent $dbEvent): void
    {
        $this->items[$dbEvent->ID] = $dbEvent;
    }

    /**
     * @return DbEvent[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function render(): HtmlDataObjectCollection
    {
        $htmlDataObjectCollection = new HtmlDataObjectCollection();
        foreach ($this->items as $dbEvent) {
            $htmlDataObjectCollection->add(
                htmlDataObject: $dbEvent->render()
            );
        }
        return $htmlDataObjectCollection;
    }

    public function getMinYear(): int
    {
        return (int)min(array_map(fn(DbEvent $dbEvent) => $dbEvent->dateFrom->format(format: 'Y'), $this->items));
    }

    public function getMaxYear(): int
    {
        return (int)max(array_map(fn(DbEvent $dbEvent) => $dbEvent->dateTo->format(format: 'Y'), $this->items));
    }

    public function renderYearNavigation(
        int $selectedYear,
        string $path
    ): string {
        $jArr = [];
        for ($i = $this->getMaxYear(); $i >= $this->getMinYear(); $i--) {
            if ($i === $selectedYear) {
                $jArr[] = '<strong>' . $i . '</strong>';
            } else {
                $jArr[] = '<a href="' . str_replace(
                        search: '0',
                        replace: $i,
                        subject: $path
                    ) . '">' . $i . '</a>';
            }
        }
        return '<p>' . implode(
                separator: " | ",
                array: $jArr
            ) . '</p>';
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }

    public function first(): DbEvent
    {
        return current(array: $this->items);
    }
}