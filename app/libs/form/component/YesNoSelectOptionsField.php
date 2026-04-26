<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form\component;

use actra\backend\libs\form\component\SearchSelectOptionsField;
use actra\yuf\form\FormOptions;
use actra\yuf\html\HtmlText;

class YesNoSelectOptionsField extends SearchSelectOptionsField
{
    public function __construct(
        string $name,
        HtmlText $label
    ) {
        $formOptions = new FormOptions();
        $formOptions->addItem(
            key: 'no',
            htmlText: HtmlText::encoded(textContent: 'Nein')
        );
        $formOptions->addItem(
            key: 'yes',
            htmlText: HtmlText::encoded(textContent: 'Ja')
        );
        parent::__construct(
            name: $name,
            label: $label,
            formOptions: $formOptions,
            initialValue: ''
        );
    }
}