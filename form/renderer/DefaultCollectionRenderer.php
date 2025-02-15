<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\FormCollection;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class DefaultCollectionRenderer extends FormRenderer
{

    public function __construct(private readonly FormCollection $formCollection)
    {
    }

    public function prepare(): void
    {
        $componentTag = new HtmlTag($this->formCollection->name, false);

        if ($this->formCollection->hasErrors(withChildElements: true)) {
            $componentTag->addHtmlTagAttribute(new HtmlTagAttribute('class', 'has-error', true));
        }

        foreach ($this->formCollection->childComponents as $childComponent) {
            $componentTag->addTag($childComponent->getHtmlTag());
        }

        $this->setHtmlTag($componentTag);
    }
}