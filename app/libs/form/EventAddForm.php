<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\ActraBackend;
use actra\backend\libs\auth\MyAuthUser;
use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\BooleanField;
use actra\yuf\form\component\field\CheckboxOptionsField;
use actra\yuf\form\component\field\DateField;
use actra\yuf\form\component\field\SelectOptionsField;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\field\TimeField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\backend\AuthUserHelper;
use app\libs\db\DbClubRepository;
use app\libs\db\DbEventCategoryRepository;
use app\libs\db\DbEventRepository;
use app\libs\email\EmailToWebmaster;
use app\settings\EventCategoryCollection;
use app\settings\EventCategoryEnum;
use app\view\backend\php\events;
use DateTimeImmutable;

class EventAddForm extends Form
{
    public readonly int $eventID;
    private readonly SelectOptionsField $clubField;
    private readonly DateField $dateFromField;
    private readonly DateField $dateToField;
    private readonly TextField $titleField;
    private readonly TextField $locationField;
    private readonly CheckboxOptionsField $categoryField;
    private readonly BooleanField $exportField;
    private readonly TimeField $timeFromField;
    private readonly TimeField $timeToField;
    private readonly TextField $timeField;
    private readonly TextAreaField $notesField;

    public function __construct(private readonly MyAuthUser $myAuthUser)
    {
        parent::__construct(name: 'EventAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->clubField = new SelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Durchführender Verein'),
                formOptions: DbClubRepository::listMemberClubs(
                    memberID: $myAuthUser->ID
                )->getFormOptions(),
                initialValue: null,
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie einen Verein aus.')
            )
        );
        $this->addField(
            formField: $this->dateFromField = new DateField(
                name: 'dateFromField',
                label: HtmlText::encoded(textContent: 'Datum von'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Datum ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie das Datum ein.')
            )
        );
        $this->addField(
            formField: $this->dateToField = new DateField(
                name: 'dateToField',
                label: HtmlText::encoded(textContent: 'Datum bis'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Datum ein.')
            )
        );
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel/Anlass'),
                value: null,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel/Anlass ein.')
            )
        );
        $this->addField(
            formField: $this->locationField = new TextField(
                name: 'locationField',
                label: HtmlText::encoded(textContent: 'Ort/Schiessplatz'),
                value: null,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Ort/Schiessplatz ein.')
            )
        );
        $this->addField(
            formField: $this->categoryField = new CheckboxOptionsField(
                name: 'categoryField',
                label: HtmlText::encoded(textContent: 'Kategorie(n)'),
                formOptions: EventCategoryCollection::createFullList()->getFormOptions(),
                initialValues: [],
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Kategorie aus.')
            )
        );
        $this->addField(
            formField: $this->exportField = new BooleanField(
                name: 'exportField',
                label: HtmlText::encoded(textContent: 'Kalenderexport'),
                isCheckedByDefault: false
            )
        );
        $this->addField(
            formField: $this->timeFromField = new TimeField(
                name: 'timeFromField',
                label: HtmlText::encoded(textContent: 'Zeit von'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Uhrzeit ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Uhrzeit ein.')
            )
        );
        $this->timeFromField->fieldInfo = HtmlText::encoded(textContent: 'Für den Kalenderexport.');
        $this->addField(
            formField: $this->timeToField = new TimeField(
                name: 'timeToField',
                label: HtmlText::encoded(textContent: 'Zeit bis'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Uhrzeit ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Uhrzeit ein.')
            )
        );
        $this->timeToField->fieldInfo = HtmlText::encoded(textContent: 'Für den Kalenderexport.');
        $this->addField(
            formField: $this->timeField = new TextField(
                name: 'timeField',
                label: HtmlText::encoded(textContent: 'Zeit'),
                value: null
            )
        );
        $this->timeField->fieldInfo = HtmlText::encoded(textContent: 'Zur Anzeige auf der Website.');
        $this->addField(
            formField: $this->notesField = new TextAreaField(
                name: 'notesField',
                label: HtmlText::encoded(textContent: 'Bemerkungen'),
                value: null
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: events::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $myAuthUser = $this->myAuthUser;
        $dateFrom = new DateTimeImmutable(datetime: $this->dateFromField->getRawValue());
        $dateToRaw = $this->dateToField->getRawValue();
        $this->eventID = DbEventRepository::insert(
            myAuthUser: $myAuthUser,
            clubID: (int)$this->clubField->getRawValue(),
            dateFrom: $dateFrom,
            dateTo: $dateToRaw === '' ? $dateFrom : new DateTimeImmutable(datetime: $dateToRaw),
            time: $this->timeField->getRawValue(),
            title: $this->titleField->getRawValue(),
            location: $this->locationField->getRawValue(),
            notes: $this->notesField->getRawValue(),
            export: $this->exportField->isChecked(),
            timeFrom: new DateTimeImmutable(
                datetime: $this->dateFromField->getRawValue() . ' ' . $this->timeFromField->getRawValue()
            ),
            timeTo: new DateTimeImmutable(
                datetime: $this->dateToField->getRawValue() . ' ' . $this->timeToField->getRawValue()
            )
        );
        foreach ($this->categoryField->getAddedValues() as $addedValue) {
            DbEventCategoryRepository::insert(
                eventID: $this->eventID,
                eventCategoryEnum: EventCategoryEnum::from(value: $addedValue)
            );
        }
        if (AuthUserHelper::isAdmin()) {
            DbEventRepository::confirm(ID: $this->eventID);
        } else {
            EmailToWebmaster::send(
                subject: 'Anlass bei ' . $_SERVER['SERVER_NAME'] . ' wurde geändert',
                message: implode(
                    separator: PHP_EOL,
                    array: [
                        'Guten Tag',
                        '',
                        'Es gibt einen neuen Anlass bei ' . $_SERVER['SERVER_NAME'] . '. Bitte prüfen und veröffentlichen oder löschen Sie diesen Anlass.',
                        '',
                        'Freundliche Grüsse',
                        '',
                        ActraBackend::get()->mailerSettings->signature,
                    ]
                ),
            );
        }
        return true;
    }
}