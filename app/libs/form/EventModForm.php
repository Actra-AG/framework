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
use app\libs\db\DbClubRepository;
use app\libs\db\DbEvent;
use app\libs\db\DbEventCategoryRepository;
use app\libs\db\DbEventRepository;
use app\libs\email\EmailToWebmaster;
use app\settings\EventCategoryCollection;
use app\settings\EventCategoryEnum;
use app\view\backend\php\event;
use DateTimeImmutable;

class EventModForm extends Form
{
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

    public function __construct(private readonly DbEvent $dbEvent)
    {
        parent::__construct(name: 'EventModForm-' . $dbEvent->ID);
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->clubField = new SelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Durchführender Verein'),
                formOptions: DbClubRepository::listMemberClubs(
                    memberID: MyAuthUser::get()->ID
                )->getFormOptions(),
                initialValue: (string)$dbEvent->clubID,
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie einen Verein aus.')
            )
        );
        $this->addField(
            formField: $this->dateFromField = new DateField(
                name: 'dateFromField',
                label: HtmlText::encoded(textContent: 'Datum von'),
                value: $dbEvent->dateFrom->format(format: 'Y-m-d'),
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Datum ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie das Datum ein.')
            )
        );
        $this->addField(
            formField: $this->dateToField = new DateField(
                name: 'dateToField',
                label: HtmlText::encoded(textContent: 'Datum bis'),
                value: $dbEvent->dateTo->format(format: 'Y-m-d'),
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie ein gültiges Datum ein.')
            )
        );
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel/Anlass'),
                value: $dbEvent->title,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel/Anlass ein.')
            )
        );
        $this->addField(
            formField: $this->locationField = new TextField(
                name: 'locationField',
                label: HtmlText::encoded(textContent: 'Ort/Schiessplatz'),
                value: $dbEvent->location,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Ort/Schiessplatz ein.')
            )
        );
        $this->addField(
            formField: $this->categoryField = new CheckboxOptionsField(
                name: 'categoryField',
                label: HtmlText::encoded(textContent: 'Kategorie(n)'),
                formOptions: EventCategoryCollection::createFullList()->getFormOptions(),
                initialValues: $dbEvent->eventCategoryCollection->listIds(),
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie mindestens eine Kategorie aus.')
            )
        );
        $this->addField(
            formField: $this->exportField = new BooleanField(
                name: 'exportField',
                label: HtmlText::encoded(textContent: 'Kalenderexport'),
                isCheckedByDefault: $dbEvent->export
            )
        );
        $this->addField(
            formField: $this->timeFromField = new TimeField(
                name: 'timeFromField',
                label: HtmlText::encoded(textContent: 'Zeit von'),
                value: $dbEvent->timeFrom->format(format: 'H:i'),
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Uhrzeit ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Uhrzeit ein.')
            )
        );
        $this->timeFromField->fieldInfo = HtmlText::encoded(textContent: 'Für den Kalenderexport.');
        $this->addField(
            formField: $this->timeToField = new TimeField(
                name: 'timeToField',
                label: HtmlText::encoded(textContent: 'Zeit bis'),
                value: $dbEvent->timeTo->format(format: 'H:i'),
                invalidError: HtmlText::encoded(textContent: 'Bitte geben Sie eine gültige Uhrzeit ein.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie die Uhrzeit ein.')
            )
        );
        $this->timeToField->fieldInfo = HtmlText::encoded(textContent: 'Für den Kalenderexport.');
        $this->addField(
            formField: $this->timeField = new TextField(
                name: 'timeField',
                label: HtmlText::encoded(textContent: 'Zeit'),
                value: $dbEvent->timeFormatted
            )
        );
        $this->timeField->fieldInfo = HtmlText::encoded(textContent: 'Zur Anzeige auf der Website.');
        $this->addField(
            formField: $this->notesField = new TextAreaField(
                name: 'notesField',
                label: HtmlText::encoded(textContent: 'Bemerkungen'),
                value: $dbEvent->notes
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: event::getPath(ID: $dbEvent->ID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $eventID = $this->dbEvent->ID;
        $dateFrom = new DateTimeImmutable(datetime: $this->dateFromField->getRawValue());
        $dateToRaw = $this->dateToField->getRawValue();
        DbEventRepository::update(
            ID: $eventID,
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
                eventID: $eventID,
                eventCategoryEnum: EventCategoryEnum::from(value: $addedValue)
            );
        }
        foreach ($this->categoryField->getRemovedValues() as $removedValue) {
            DbEventCategoryRepository::delete(
                eventID: $eventID,
                eventCategoryEnum: EventCategoryEnum::from(value: $removedValue)
            );
        }
        $dbAuthUser = MyAuthUser::get()->dbAuthUser;
        EmailToWebmaster::send(
            subject: 'Anlass bei ' . $_SERVER['SERVER_NAME'] . ' wurde geändert',
            message: implode(
                separator: PHP_EOL,
                array: [
                    'Guten Tag',
                    '',
                    'Der Anlass ' . $this->dbEvent->title . ' wurde bei ' . $_SERVER['SERVER_NAME'] . ' durch den Benutzer ' . $dbAuthUser->firstName . ' ' . $dbAuthUser->lastName . ' geändert.',
                    '',
                    'Freundliche Grüsse',
                    '',
                    ActraBackend::get()->mailerSettings->signature,
                ]
            ),
        );
        return true;
    }
}