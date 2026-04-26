<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\BooleanField;
use actra\yuf\form\component\field\DateField;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbNews;
use app\libs\db\DbNewsRepository;
use app\view\backend\php\news;
use DateTimeImmutable;

class NewsModForm extends Form
{
    private readonly DateField $dateField;
    private readonly TextField $titleField;
    private readonly TextAreaField $teaserField;
    private readonly TextAreaField $textField;
    private readonly BooleanField $archivedField;

    public function __construct(private readonly DbNews $dbNews)
    {
        parent::__construct(name: 'NewsModForm-' . $dbNews->ID);
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->dateField = new DateField(
                name: 'dateField',
                label: HtmlText::encoded(textContent: 'Datum'),
                value: $dbNews->date->format(format: 'Y-m-d'),
                invalidError: HtmlText::encoded(textContent: 'Das Datum ist ungültig.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie das Datum ein.')
            )
        );
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel'),
                value: $dbNews->title,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel ein.')
            )
        );
        $this->addField(
            formField: $this->teaserField = new TextAreaField(
                name: 'teaserField',
                label: HtmlText::encoded(textContent: 'Anriss'),
                value: $dbNews->teaser
            )
        );
        $this->addField(
            formField: $this->textField = new TextAreaField(
                name: 'textField',
                label: HtmlText::encoded(textContent: 'Haupttext'),
                value: $dbNews->htmlContent
            )
        );
        $this->addField(
            formField: $this->archivedField = new BooleanField(
                name: 'archivedField',
                label: HtmlText::encoded(textContent: 'Eintrag archivieren?'),
                isCheckedByDefault: $dbNews->isArchive
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'speichern'),
                cancelLink: news::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        DbNewsRepository::update(
            ID: $this->dbNews->ID,
            date: new DateTimeImmutable(datetime: $this->dateField->getRawValue()),
            title: $this->titleField->getRawValue(),
            teaser: $this->teaserField->getRawValue(),
            text: $this->textField->getRawValue(),
            archived: $this->archivedField->isChecked()
        );
        return true;
    }
}