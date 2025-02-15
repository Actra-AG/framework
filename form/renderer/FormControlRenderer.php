<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\FormControl;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class FormControlRenderer extends FormRenderer
{
    public function __construct(private readonly FormControl $formControl)
    {
    }

    public function prepare(): void
    {
        $formControl = $this->formControl;

        $buttonTag = new HtmlTag('button', false, [
            new HtmlTagAttribute('type', 'submit', true),
            new HtmlTagAttribute('name', $formControl->name, true),
        ]);
        $buttonTag->addText(htmlText: $formControl->submitLabel);

        $divTag = new HtmlTag('div', false, [new HtmlTagAttribute('class', 'form-control', true)]);
        $divTag->addTag($buttonTag);

        if (!is_null($formControl->cancelLink)) {
            $aTag = new HtmlTag('a', false, [
                new HtmlTagAttribute('href', $formControl->cancelLink, true),
                new HtmlTagAttribute('class', 'link-cancel', true),
            ]);
            $aTag->addText($formControl->cancelLabel);
            $divTag->addTag($aTag);
        }

        $this->setHtmlTag($divTag);
    }
}