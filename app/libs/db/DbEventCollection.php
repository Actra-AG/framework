<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\yuf\html\HtmlDataObjectCollection;
use DateTimeImmutable;

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

    public function getMinEventDate(): DateTimeImmutable
    {
        return min(array_map(fn(DbEvent $dbEvent) => $dbEvent->dateFrom, $this->items));
    }

    public function getMaxEventDate(): DateTimeImmutable
    {
        return max(array_map(fn(DbEvent $dbEvent) => $dbEvent->dateTo, $this->items));
    }

    public function getMaxLastModified(): DateTimeImmutable
    {
        $maxLastModified = null;
        foreach ($this->items as $dbEvent) {
            if (
              $dbEvent->lastModified > $maxLastModified
              || $maxLastModified === null
            ) {
                $maxLastModified = $dbEvent->lastModified;
            }
        }

        return $maxLastModified;
    }

    public function getAvailableYears(): array
    {
        $minYear = (int)$this->getMinEventDate()->format(format: 'Y');
        $maxYear = (int)$this->getMaxEventDate()->format(format: 'Y');
        $availableYears = [];
        for ($i = $minYear; $i <= $maxYear; $i++) {
            $availableYears[] = $i;
        }

        return $availableYears;
    }

    public function getAvailableMonths(int $year): array
    {
        $minDate = $this->getMinEventDate();
        $maxDate = $this->getMaxEventDate();
        $minYear = (int)$minDate->format(format: 'Y');
        $maxYear = (int)$maxDate->format(format: 'Y');
        if ($year < $minYear || $year > $maxYear) {
            return [];
        }
        $startMonth = ($year === $minYear) ? (int)$minDate->format(format: 'm') : 1;
        $endMonth = ($year === $maxYear) ? (int)$maxDate->format(format: 'm') : 12;
        return range(start: $startMonth, end: $endMonth);
    }

    public function renderYearNavigation(
      int $selectedYear,
      string $path
    ): string {
        $availableYears = $this->getAvailableYears();
        rsort(array: $availableYears);
        $jArr = [];
        foreach ($availableYears as $year) {
            if ($year === $selectedYear) {
                $jArr[] = '<strong>' . $year . '</strong>';
            } else {
                $jArr[] = '<a href="' . str_replace(
                    search: '0',
                    replace: $year,
                    subject: $path
                  ) . '">' . $year . '</a>';
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