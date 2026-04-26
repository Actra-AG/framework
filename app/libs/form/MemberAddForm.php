<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\libs\auth\MyAuthUser;
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
use app\libs\db\DbMemberClubRepository;
use app\libs\db\DbMemberRepository;
use app\view\backend\php\members;
use DateTimeImmutable;

class MemberAddForm extends Form
{
    public readonly int $userID;
    private readonly SelectOptionsField $clubField;
    private readonly SelectOptionsField $genderField;
    private readonly TextField $firstNameField;
    private readonly TextField $lastNameField;
    private readonly TextField $streetField;
    private readonly TextField $zipField;
    private readonly TextField $cityField;
    private readonly TextField $licenseField;
    private readonly PhoneNumberField $phoneField;
    private readonly EmailField $emailField;
    private readonly DateField $dateOfBirthField;
    private readonly TextAreaField $notesField;
    private readonly BooleanField $honoraryField;
    private readonly AmountField $honoredField;
    private readonly CheckboxOptionsField $userGroupsField;
    private readonly BooleanField $activeField;
    private readonly CheckboxOptionsField $clubCalendarField;


    public function __construct()
    {
        parent::__construct(name: 'MemberAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->clubField = new SelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Verein'),
                formOptions: DbClubRepository::listAll()->getFormOptions(),
                initialValue: '',
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
                initialValue: '',
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie eine Anrede aus.')
            )
        );
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
            formField: $this->streetField = new TextField(
                name: 'street',
                label: HtmlText::encoded(textContent: 'Strasse'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Strasse ein.')
            )
        );
        $this->addField(
            formField: $this->zipField = new TextField(
                name: 'zip',
                label: HtmlText::encoded(textContent: 'PLZ'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Postleitzahl ein.')
            )
        );
        $this->addField(
            formField: $this->cityField = new TextField(
                name: 'city',
                label: HtmlText::encoded(textContent: 'Ort'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Ort ein.')
            )
        );
        $this->addField(
            formField: $this->licenseField = new TextField(
                name: 'license',
                label: HtmlText::encoded(textContent: 'Lizenznummer')
            )
        );
        $this->addField(
            formField: $this->phoneField = new PhoneNumberField(
                name: 'phone',
                label: HtmlText::encoded(textContent: 'Telefonnummer'),
                value: null,
                invalidErrorMessage: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Telefonnummer ein.')
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
            formField: $this->dateOfBirthField = new DateField(
                name: 'dateOfBirth',
                label: HtmlText::encoded(textContent: 'Geburtsdatum'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Geburtsdatum ein.')
            )
        );
        $this->addField(
            formField: $this->notesField = new TextAreaField(
                name: 'notes',
                label: HtmlText::encoded(textContent: 'Notizen')
            )
        );
        $this->addField(
            formField: $this->honoraryField = new BooleanField(
                name: 'honorary',
                label: HtmlText::encoded(textContent: 'Ehrenmitglied'),
                isCheckedByDefault: false
            )
        );
        $this->addField(
            formField: $this->honoredField = new AmountField(
                name: 'honored',
                label: HtmlText::encoded(textContent: 'Ernannt'),
                valueIsFloat: false
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
                formOptions: DbAuthGroupRepository::listAll()->getFormOptions(),
                initialValues: [],
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Benutzergruppe aus.')
            )
        );
        $this->addField(
            formField: $this->clubCalendarField = new CheckboxOptionsField(
                name: 'clubCalendar',
                label: HtmlText::encoded(textContent: 'Jahresprogramm'),
                formOptions: DbClubRepository::listAll()->getFormOptions(),
                initialValues: []
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
        if (!is_null(value: DbAuthUserRepository::selectByEmail(email: $this->emailField->getRawValue()))) {
            $this->addError(
                errorMessage: 'Die eingegebene E-Mail-Adresse wird bereits verwendet.',
                isEncodedForRendering: true
            );

            return false;
        }
        $this->userID = DbAuthUserRepository::insert(
            email: $this->emailField->getRawValue(),
            active: $this->activeField->isChecked(),
            firstName: $this->firstNameField->getRawValue(),
            lastName: $this->lastNameField->getRawValue()
        );
        foreach ($this->userGroupsField->getRawValue() as $userGroupValue) {
            DbAuthUserGroupRepository::insert(
                userID: $this->userID,
                groupID: (int)$userGroupValue
            );
        }
        $birthDate = $this->dateOfBirthField->getRawValue();
        DbMemberRepository::insert(
            ID: $this->userID,
            clubID: (int)$this->clubField->getRawValue(),
            registeredBy: MyAuthUser::get()->dbAuthUser->ID,
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
        foreach ($this->clubCalendarField->getRawValue() as $item) {
            DbMemberClubRepository::insert(
                memberID: $this->userID,
                clubID: (int)$item
            );
        }

        return true;
    }
}