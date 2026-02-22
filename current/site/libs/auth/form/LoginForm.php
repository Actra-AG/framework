<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\form;

use framework\form\component\collection\Form;
use framework\form\component\field\EmailField;
use framework\form\component\FormControl;
use framework\html\HtmlText;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\MyAuthenticator;

class LoginForm extends Form
{
    private readonly EmailField $emailField;

    public function __construct()
    {
        parent::__construct(name: 'LoginForm');
        $this->addCssClass(className: 'form');
        $this->addCssClass(className: 'form-login');
        $this->addField(
            formField: $this->emailField = new EmailField(
                name: 'email',
                label: HtmlText::encoded(textContent: 'E-Mail'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Sie haben eine ungültige E-Mail-Adresse eingegeben.'),
                requiredError: HtmlText::encoded(textContent: 'Geben Sie Ihre E-Mail-Adresse ein.'),
            )
        );
        $this->emailField->autoFocus = true;
        $this->emailField->renderRequiredAbbr = false;
        $this->addComponent(
            formComponent: new FormControl(
                name: 'submit',
                submitLabel: HtmlText::encoded(textContent: 'weiter'),
            )
        );
    }

    public function process(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $dbAuthUserItem = DbAuthUser::selectByEmail(email: $this->emailField->getRawValue());
        if (is_null(value: $dbAuthUserItem)) {
            return true;
        }
        MyAuthenticator::get()->createAndSendAuthToken(dbAuthUserItem: $dbAuthUserItem);

        return true;
    }
}