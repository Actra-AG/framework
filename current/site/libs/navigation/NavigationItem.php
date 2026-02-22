<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace site\libs\navigation;

use actra\yuf\html\HtmlDataObject;
use actra\yuf\html\HtmlDataObjectCollection;

readonly class NavigationItem
{
    public function __construct(
        public string $name,
        public string $url,
        public string $label,
        public array  $subNavigation
    )
    {
    }

    public function render(
        array $activeNavigationItems,
        int   $level
    ): HtmlDataObject
    {
        $htmlDataObject = new HtmlDataObject();
        $htmlDataObject->addTextElement(
            propertyName: 'id',
            content: $this->name,
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'href',
            content: $this->url,
            isEncodedForRendering: true
        );
        $htmlDataObject->addTextElement(
            propertyName: 'label',
            content: $this->label,
            isEncodedForRendering: true
        );
        if (
            $this->subNavigation === []
            || !array_key_exists(
                key: $level,
                array: $activeNavigationItems
            )
            || $activeNavigationItems[$level] !== $this->name
        ) {
            $htmlDataObject->addHtmlDataObjectsArray(
                propertyName: 'subNavigation',
                htmlDataObjectsArray: null
            );
        } else {
            $subNavigation = new HtmlDataObjectCollection();
            foreach ($this->subNavigation as $subItem) {
                $subNavigation->add(
                    htmlDataObject: new NavigationItem(
                        name: $subItem['id'],
                        url: $subItem['href'],
                        label: $subItem['label'],
                        subNavigation: []
                    )->render(
                        activeNavigationItems: $activeNavigationItems,
                        level: $level + 1
                    )
                );
            }
            $htmlDataObject->addHtmlDataObjectsArray(
                propertyName: 'subNavigation',
                htmlDataObjectsArray: $subNavigation->items
            );
        }

        return $htmlDataObject;
    }
}