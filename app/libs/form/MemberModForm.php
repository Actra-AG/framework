<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\libs\db\DbAuthGroupRepository;
use actra\backend\libs\db\DbAuthUserGroupRepository;
use actra\backend\libs\db\DbAuthUserRepository;
use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\AmountField;
use actra\yuf\form\component\field\BooleanField;
use actra\yuf\form\component\field\CheckboxOptionsField;
use actra\yuf\form\component\field\DateField;
use actra\yuf\form\component\field\EmailField;
use actra\yuf\form\component\field\PhoneNumberField;
use actra\yuf\form\component\field\SelectOptionsField;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\form\FormOptions;
use actra\yuf\html\HtmlText;
use app\libs\db\DbClubRepository;
use app\libs\db\DbMember;
use app\libs\db\DbMemberClubRepository;
use app\libs\db\DbMemberRepository;
use app\view\backend\php\members;
use DateTimeImmutable;

class MemberModForm extends Form
{
    private readonly SelectOptionsField $clubField;
    private readonly SelectOptionsField $genderField;
    private readonly TextField $firstNameField;
    private readonly TextField $lastNameField;
    private readonly TextField $streetField;
    private readonly TextField $zipField;
    private readonly TextField $cityField;
    private readonly AmountField $licenseField;
    private readonly PhoneNumberField $phoneField;
    private readonly EmailField $emailField;
    private readonly DateField $dateOfBirthField;
    private readonly TextAreaField $notesField;
    private readonly BooleanField $honoraryField;
    private readonly AmountField $honoredField;
    private readonly CheckboxOptionsField $userGroupsField;
    private readonly BooleanField $activeField;
    private readonly CheckboxOptionsField $clubCalendarField;


    public function __construct(private readonly DbMember $dbMember)
    {
        $dbAuthUser = $dbMember->dbAuthUser;
        parent::__construct(name: 'MemberModForm-' . $dbMember->dbAuthUser->ID);
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->clubField = new SelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Verein'),
                formOptions: DbClubRepository::listAll()->getFormOptions(),
                initialValue: (string)$dbMember->clubID,
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie einen Verein aus.')
            )
        );
        $formOptions = new FormOptions();
        $formOptions->addItem(
            key: 'Frau',
            htmlText: HtmlText::encoded(textContent: 'Frau')
        );
        $formOptions->addItem(
            key: 'Herr',
            htmlText: HtmlText::encoded(textContent: 'Herr')
        );
        $this->addField(
            formField: $this->genderField = new SelectOptionsField(
                name: 'genderField',
                label: HtmlText::encoded(textContent: 'Anrede'),
                formOptions: $formOptions,
                initialValue: $dbMember->gender,
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie eine Anrede aus.')
            )
        );
        $this->addField(
            formField: $this->firstNameField = new TextField(
                name: 'firstName',
                label: HtmlText::encoded(textContent: 'Vorname'),
                value: $dbAuthUser->firstName,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Vornamen ein.')
            )
        );
        $this->addField(
            formField: $this->lastNameField = new TextField(
                name: 'lastName',
                label: HtmlText::encoded(textContent: 'Nachname'),
                value: $dbAuthUser->lastName,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Nachnamen ein.')
            )
        );
        $this->addField(
            formField: $this->streetField = new TextField(
                name: 'street',
                label: HtmlText::encoded(textContent: 'Strasse'),
                value: $dbMember->street,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Strasse ein.')
            )
        );
        $this->addField(
            formField: $this->zipField = new TextField(
                name: 'zip',
                label: HtmlText::encoded(textContent: 'PLZ'),
                value: $dbMember->zip,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Postleitzahl ein.')
            )
        );
        $this->addField(
            formField: $this->cityField = new TextField(
                name: 'city',
                label: HtmlText::encoded(textContent: 'Ort'),
                value: $dbMember->city,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Ort ein.')
            )
        );
        $this->addField(
            formField: $this->licenseField = new AmountField(
                name: 'license',
                label: HtmlText::encoded(textContent: 'Lizenznummer'),
                valueIsFloat: false,
                initialValue: $dbMember->license,
            )
        );
        $this->addField(
            formField: $this->phoneField = new PhoneNumberField(
                name: 'phone',
                label: HtmlText::encoded(textContent: 'Telefonnummer'),
                value: $dbMember->phone,
                invalidErrorMessage: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Telefonnummer ein.')
            )
        );
        $this->addField(
            formField: $this->emailField = new EmailField(
                name: 'email',
                label: HtmlText::encoded(textContent: 'E-Mail'),
                value: $dbAuthUser->email,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige E-Mail-Adresse ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die E-Mail-Adresse ein.')
            )
        );
        $birthDate = $dbMember->birthDate;
        $this->addField(
            formField: $this->dateOfBirthField = new DateField(
                name: 'dateOfBirth',
                label: HtmlText::encoded(textContent: 'Geburtsdatum'),
                value: $birthDate?->format(format: 'Y-m-d'),
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Geburtsdatum ein.')
            )
        );
        $this->addField(
            formField: $this->notesField = new TextAreaField(
                name: 'notes',
                label: HtmlText::encoded(textContent: 'Notizen'),
                value: $dbMember->notes,
            )
        );
        $this->addField(
            formField: $this->honoraryField = new BooleanField(
                name: 'honorary',
                label: HtmlText::encoded(textContent: 'Ehrenmitglied'),
                isCheckedByDefault: $dbMember->honorary,
            )
        );
        $this->addField(
            formField: $this->honoredField = new AmountField(
                name: 'honored',
                label: HtmlText::encoded(textContent: 'Ernannt'),
                valueIsFloat: false,
                initialValue: $dbMember->honored,
            )
        );
        $this->addField(
            formField: $this->activeField = new BooleanField(
                name: 'active',
                label: HtmlText::encoded(textContent: 'aktiver Zugang'),
                isCheckedByDefault: $dbAuthUser->isActive,
            )
        );
        $this->addField(
            formField: $this->userGroupsField = new CheckboxOptionsField(
                name: 'userGroups',
                label: HtmlText::encoded(textContent: 'Benutzergruppen'),
                formOptions: DbAuthGroupRepository::listAll()->getFormOptions(),
                initialValues: DbAuthGroupRepository::listByUserID(userID: $dbAuthUser->ID)->listIDs(),
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Benutzergruppe aus.')
            )
        );
        $this->addField(
            formField: $this->clubCalendarField = new CheckboxOptionsField(
                name: 'clubCalendar',
                label: HtmlText::encoded(textContent: 'Jahresprogramm'),
                formOptions: DbClubRepository::listAll()->getFormOptions(),
                initialValues: DbClubRepository::listMemberClubs(memberID: $dbAuthUser->ID)->listIDs(),
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: members::getPath()
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
            && !is_null(value: DbAuthUserRepository::selectByEmail(email: $this->emailField->getRawValue()))
        ) {
            $this->addError(
                errorMessage: 'Die eingegebene E-Mail-Adresse wird bereits verwendet.',
                isEncodedForRendering: true
            );

            return false;
        }
        $dbMember = $this->dbMember;
        $userID = $dbMember->dbAuthUser->ID;
        DbAuthUserRepository::update(
            ID: $userID,
            email: $this->emailField->getRawValue(),
            active: $this->activeField->isChecked(),
            firstName: $this->firstNameField->getRawValue(),
            lastName: $this->lastNameField->getRawValue()
        );
        foreach ($this->userGroupsField->getAddedValues() as $userGroupValue) {
            DbAuthUserGroupRepository::insert(
                userID: $userID,
                groupID: (int)$userGroupValue
            );
        }
        foreach ($this->userGroupsField->getRemovedValues() as $userGroupValue) {
            DbAuthUserGroupRepository::delete(
                userID: $userID,
                groupID: (int)$userGroupValue
            );
        }
        $birthDate = $this->dateOfBirthField->getRawValue();
        DbMemberRepository::update(
            ID: $userID,
            clubID: (int)$this->clubField->getRawValue(),
            accepted: new DateTimeImmutable(),
            licence: (int)$this->licenseField->getRawValue(),
            gender: $this->genderField->getRawValue(),
            street: $this->streetField->getRawValue(),
            zip: $this->zipField->getRawValue(),
            city: $this->cityField->getRawValue(),
            phone: $this->phoneField->getRawValue(),
            comment: $this->notesField->getRawValue(),
            notes: $this->notesField->getRawValue(),
            birthdate: $birthDate === '' ? null : new DateTimeImmutable(datetime: $birthDate),
            honorary: $this->honoraryField->isChecked(),
            honored: (int)$this->honoredField->getRawValue(),
        );
        foreach ($this->clubCalendarField->getAddedValues() as $addedValue) {
            DbMemberClubRepository::insert(
                memberID: $userID,
                clubID: (int)$addedValue
            );
        }
        foreach ($this->clubCalendarField->getRemovedValues() as $removedValue) {
            DbMemberClubRepository::delete(
                memberID: $userID,
                clubID: (int)$removedValue
            );
        }
        return true;
    }
}