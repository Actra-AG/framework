<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\FormComponent;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class DefaultComponentRenderer extends FormRenderer
{
    public function __construct(private readonly FormComponent $formComponent)
    {
    }

    public function prepare(): void
    {
        $componentTag = new HtmlTag($this->formComponent->name, false);

        if ($this->formComponent->hasErrors(withChildElements: true)) {
            $componentTag->addHtmlTagAttribute(new HtmlTagAttribute('class', 'has-error', true));
        }
        $this->setHtmlTag($componentTag);
    }
}