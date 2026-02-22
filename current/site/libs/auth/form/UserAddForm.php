<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland - www.actra.ch
 */

namespace site\libs\auth\form;

use framework\form\component\collection\Form;
use framework\form\component\field\BooleanField;
use framework\form\component\field\CheckboxOptionsField;
use framework\form\component\field\EmailField;
use framework\form\component\field\TextField;
use framework\form\component\FormControl;
use framework\html\HtmlText;
use site\libs\auth\db\DbAuthGroup;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\db\DbAuthUserGroup;
use site\view\backend\php\users;

class UserAddForm extends Form
{
    public readonly int $newUserID;
    private readonly TextField $firstNameField;
    private readonly TextField $lastNameField;
    private readonly EmailField $emailField;
    private readonly CheckboxOptionsField $userGroupsField;
    private readonly BooleanField $activeField;

    public function __construct()
    {
        parent::__construct(name: 'UserAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->firstNameField = new TextField(
                name: 'firstName',
                label: HtmlText::encoded(textContent: 'Vorname'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Vornamen ein.')
            )
        );
        $this->addField(
            formField: $this->lastNameField = new TextField(
                name: 'lastName',
                label: HtmlText::encoded(textContent: 'Nachname'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Nachnamen ein.')
            )
        );
        $this->addField(
            formField: $this->emailField = new EmailField(
                name: 'email',
                label: HtmlText::encoded(textContent: 'E-Mail'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die E-Mail-Adresse ein.')
            )
        );
        $this->addField(
            formField: $this->activeField = new BooleanField(
                name: 'active',
                label: HtmlText::encoded(textContent: 'aktiver Zugang'),
                isCheckedByDefault: false
            )
        );
        $this->addField(
            formField: $this->userGroupsField = new CheckboxOptionsField(
                name: 'userGroups',
                label: HtmlText::encoded(textContent: 'Benutzergruppen'),
                formOptions: DbAuthGroup::listAll()->getFormOptions(),
                initialValues: [],
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Benutzergruppe aus.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'speichern'),
                cancelLink: users::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        if (!is_null(value: DbAuthUser::selectByEmail(email: $this->emailField->getRawValue()))) {
            $this->addError(
                errorMessage: 'Die eingegebene E-Mail-Adresse wird bereits verwendet.',
                isEncodedForRendering: true
            );

            return false;
        }
        $newUserID = DbAuthUser::insert(
            email: $this->emailField->getRawValue(),
            active: ($this->activeField->getRawValue() === 1),
            firstName: $this->firstNameField->getRawValue(),
            lastName: $this->lastNameField->getRawValue()
        );
        foreach ($this->userGroupsField->getRawValue() as $userGroupValue) {
            DbAuthUserGroup::insert(
                userID: $newUserID,
                groupID: (int)$userGroupValue
            );
        }
        $this->newUserID = $newUserID;

        return true;
    }
}