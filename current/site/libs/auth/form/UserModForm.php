<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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
use site\libs\auth\db\DbAuthUserItem;
use site\view\backend\php\user;

class UserModForm extends Form
{
    private readonly TextField $firstNameField;
    private readonly TextField $lastNameField;
    private readonly EmailField $emailField;
    private readonly CheckboxOptionsField $userGroupsField;
    private readonly BooleanField $activeField;

    public function __construct(private readonly DbAuthUserItem $dbAuthUserItem)
    {
        parent::__construct(name: 'UserModForm-' . $this->dbAuthUserItem->ID);
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->firstNameField = new TextField(
                name: 'firstName',
                label: HtmlText::encoded(textContent: 'Vorname'),
                value: $dbAuthUserItem->firstName,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Vornamen ein.')
            )
        );
        $this->addField(
            formField: $this->lastNameField = new TextField(
                name: 'lastName',
                label: HtmlText::encoded(textContent: 'Nachname'),
                value: $dbAuthUserItem->lastName,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Nachnamen ein.')
            )
        );
        $this->addField(
            formField: $this->emailField = new EmailField(
                name: 'email',
                label: HtmlText::encoded(textContent: 'E-Mail'),
                value: $dbAuthUserItem->email,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die E-Mail-Adresse ein.')
            )
        );
        $this->addField(
            formField: $this->activeField = new BooleanField(
                name: 'active',
                label: HtmlText::encoded(textContent: 'aktiver Zugang'),
                isCheckedByDefault: $dbAuthUserItem->isActive
            )
        );
        $this->addField(
            formField: $this->userGroupsField = new CheckboxOptionsField(
                name: 'userGroups',
                label: HtmlText::encoded(textContent: 'Benutzergruppen'),
                formOptions: DbAuthGroup::listAll()->getFormOptions(),
                initialValues: DbAuthGroup::listByUserID(userID: $this->dbAuthUserItem->ID)->listIDs(),
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Benutzergruppe aus.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: user::getPath(ID: $this->dbAuthUserItem->ID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        if (
            $this->emailField->valueHasChanged()
            && !is_null(value: DbAuthUser::selectByEmail(email: $this->emailField->getRawValue()))
        ) {
            $this->addError(
                errorMessage: 'Die eingegebene E-Mail-Adresse wird bereits verwendet.',
                isEncodedForRendering: true
            );

            return false;
        }
        $userID = $this->dbAuthUserItem->ID;
        DbAuthUser::update(
            ID: $userID,
            email: $this->emailField->getRawValue(),
            active: ($this->activeField->getRawValue() === 1),
            firstName: $this->firstNameField->getRawValue(),
            lastName: $this->lastNameField->getRawValue()
        );
        foreach ($this->userGroupsField->getAddedValues() as $userGroupValue) {
            DbAuthUserGroup::insert(
                userID: $userID,
                groupID: (int)$userGroupValue
            );
        }
        foreach ($this->userGroupsField->getRemovedValues() as $userGroupValue) {
            DbAuthUserGroup::delete(
                userID: $userID,
                groupID: (int)$userGroupValue
            );
        }

        return true;
    }
}