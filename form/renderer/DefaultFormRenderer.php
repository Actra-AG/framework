<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\renderer;

use framework\form\component\collection\Form;
use framework\form\component\FormField;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class DefaultFormRenderer extends FormRenderer
{
    public function __construct(private readonly Form $form)
    {
    }

    public function prepare(): void
    {
        $form = $this->form;
        $attributes = [
            new HtmlTagAttribute(
                name: 'method',
                value: ($form->methodPost ? 'post' : 'get'),
                valueIsEncodedForRendering: true
            ),
            new HtmlTagAttribute(
                name: 'action',
                value: '?' . $form->sentIndicator,
                valueIsEncodedForRendering: true
            ),
        ];
        $cssClasses = $form->getCssClasses();
        if (count(value: $cssClasses) > 0) {
            $attributes[] = new HtmlTagAttribute(
                name: 'class',
                value: implode(separator: ' ', array: $cssClasses),
                valueIsEncodedForRendering: true
            );
        }
        if ($form->acceptUpload) {
            $attributes[] = new HtmlTagAttribute(
                name: 'enctype',
                value: 'multipart/form-data',
                valueIsEncodedForRendering: true
            );
        }
        if ($form->disableClientValidation) {
            $attributes[] = new HtmlTagAttribute(
                name: 'novalidate',
                value: null,
                valueIsEncodedForRendering: true
            );
        }
        $htmlTag = new HtmlTag(name: 'form', selfClosing: false, htmlTagAttributes: $attributes);
        $this->renderErrors(parentTag: $htmlTag);
        foreach ($form->getChildComponents() as $childComponent) {
            $componentRenderer = $childComponent->getRenderer();
            if (is_null(value: $componentRenderer)) {
                if ($childComponent instanceof FormField) {
                    $childComponentRenderer = $form->getDefaultFormFieldRenderer(formField: $childComponent);
                } else {
                    $childComponentRenderer = $childComponent->getDefaultRenderer();
                }
                $childComponent->setRenderer(renderer: $childComponentRenderer);
            }
            $htmlTag->addTag(htmlTag: $childComponent->getHtmlTag());
        }
        $this->setHtmlTag(htmlTag: $htmlTag);
    }

    private function renderErrors(HtmlTag $parentTag): void
    {
        $form = $this->form;
        if (!$form->hasErrors(withChildElements: true)) {
            return;
        }
        $errorCollection = $form->errorCollection;
        if (!$errorCollection->hasErrors()) {
            return;
        }
        $mainAttributes = [
            new HtmlTagAttribute(
                name: 'class',
                value: 'form-error',
                valueIsEncodedForRendering: true
            ),
            new HtmlTagAttribute(
                name: 'role',
                value: 'alert',
                valueIsEncodedForRendering: true
            ),
            new HtmlTagAttribute(
                name: 'aria-live',
                value: 'assertive',
                valueIsEncodedForRendering: true
            ),
        ];
        if ($errorCollection->count() === 1) {
            $pTag = new HtmlTag(
                name: 'p',
                selfClosing: false,
                htmlTagAttributes: $mainAttributes
            );
            $strongTag = new HtmlTag(
                name: 'strong',
                selfClosing: false
            );
            $strongTag->addText(htmlText: $errorCollection->getFirstError());
            $pTag->addTag(htmlTag: $strongTag);
            $parentTag->addTag(htmlTag: $pTag);

            return;
        }
        $parentTag->addTag(
            htmlTag: $divTag = new HtmlTag(
                name: 'div',
                selfClosing: false,
                htmlTagAttributes: $mainAttributes
            )
        );
        $ulTag = new HtmlTag(
            name: 'ul',
            selfClosing: false
        );
        foreach ($errorCollection->listErrors() as $htmlText) {
            $liTag = new HtmlTag(
                name: 'li',
                selfClosing: false
            );
            $liTag->addText(htmlText: $htmlText);
            $ulTag->addTag(htmlTag: $liTag);
            $divTag->addTag(htmlTag: $ulTag);
        }
    }
}