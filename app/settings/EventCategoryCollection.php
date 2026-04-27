<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\settings;

use actra\yuf\form\FormOptions;
use actra\yuf\html\HtmlDataObjectCollection;
use actra\yuf\html\HtmlText;

class EventCategoryCollection
{
    /** @var EventCategoryEnum[] */
    private array $items = [];

    public static function createFromRawList(string $rawList): EventCategoryCollection
    {
        if ($rawList === '') {
            return new EventCategoryCollection();
        }
        $collection = new EventCategoryCollection();
        foreach (
            explode(
                separator: ',',
                string: $rawList
            ) as $rawItem
        ) {
            $collection->add(eventCategoryEnum: EventCategoryEnum::tryFrom(value: $rawItem));
        }

        return $collection;
    }

    public static function createFullList(): EventCategoryCollection
    {
        $collection = new EventCategoryCollection();
        foreach (EventCategoryEnum::cases() as $eventCategoryEnum) {
            $collection->add(eventCategoryEnum: $eventCategoryEnum);
        }

        return $collection;
    }

    public function add(EventCategoryEnum $eventCategoryEnum): void
    {
        if (!$eventCategoryEnum->userCanAccess()) {
            return;
        }
        $this->items[$eventCategoryEnum->value] = $eventCategoryEnum;
    }

    /**
     * @return EventCategoryEnum[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function render(): HtmlDataObjectCollection
    {
        $htmlCollection = new HtmlDataObjectCollection();

        foreach ($this->items as $eventCategoryEnum) {
            $htmlCollection->add(htmlDataObject: $eventCategoryEnum->render());
        }

        return $htmlCollection;
    }

    public function listIds(): array
    {
        return array_map(fn(EventCategoryEnum $eventCategoryEnum) => $eventCategoryEnum->value, $this->items);
    }

    public function getFormOptions(): FormOptions
    {
        $formOptions = new FormOptions();
        foreach ($this->items as $eventCategoryEnum) {
            $formOptions->addItem(
                key: $eventCategoryEnum->value,
                htmlText: HtmlText::encoded(textContent: $eventCategoryEnum->getTitle())
            );
        }

        return $formOptions;
    }
}