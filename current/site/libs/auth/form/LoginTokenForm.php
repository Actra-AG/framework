<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\form;

use framework\form\component\collection\Form;
use framework\form\component\field\TextField;
use framework\form\component\FormControl;
use framework\html\HtmlText;
use site\libs\auth\MyAuthenticator;

class LoginTokenForm extends Form
{
    private readonly TextField $tokenField;

    public function __construct()
    {
        parent::__construct(name: 'LoginTokenForm');
        $this->addCssClass(className: 'form');
        $this->addCssClass(className: 'form-login');
        $this->addField(
            formField: $this->tokenField = new TextField(
                name: 'token',
                label: HtmlText::encoded(textContent: 'Code'),
                value: null,
                requiredError: HtmlText::encoded(textContent: 'Geben Sie den Code ein.'),
            )
        );
        $this->tokenField->autoFocus = true;
        $this->tokenField->renderRequiredAbbr = false;
        $this->addComponent(
            formComponent: new FormControl(
                name: 'submit',
                submitLabel: HtmlText::encoded(textContent: 'anmelden'),
            )
        );
    }

    public function process(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        if (!MyAuthenticator::get()->tokenLogin(inputToken: $this->tokenField->getRawValue())) {
            $this->tokenField->addError(
                errorMessage: 'Sie haben einen ungültigen Code eingegeben.',
                isEncodedForRendering: true
            );
            return false;
        }

        return true;
    }
}