<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\libs\auth\MyAuthUser;
use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\BooleanField;
use actra\yuf\form\component\field\DateField;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbNewsRepository;
use app\view\backend\php\news;
use DateTimeImmutable;

class NewsAddForm extends Form
{
    private readonly DateField $dateField;
    private readonly TextField $titleField;
    private readonly TextAreaField $teaserField;
    private readonly TextAreaField $textField;
    private readonly BooleanField $archivedField;

    public function __construct()
    {
        parent::__construct(name: 'NewsAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->dateField = new DateField(
                name: 'dateField',
                label: HtmlText::encoded(textContent: 'Datum'),
                value: null,
                invalidError: HtmlText::encoded(textContent: 'Das Datum ist ungültig.'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie das Datum ein.')
            )
        );
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel ein.')
            )
        );
        $this->addField(
            formField: $this->teaserField = new TextAreaField(
                name: 'teaserField',
                label: HtmlText::encoded(textContent: 'Anriss')
            )
        );
        $this->addField(
            formField: $this->textField = new TextAreaField(
                name: 'textField',
                label: HtmlText::encoded(textContent: 'Haupttext')
            )
        );
        $this->addField(
            formField: $this->archivedField = new BooleanField(
                name: 'archivedField',
                label: HtmlText::encoded(textContent: 'Eintrag archivieren?'),
                isCheckedByDefault: false
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: news::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        DbNewsRepository::insert(
            registeredByUserID: MyAuthUser::get()->dbAuthUser->ID,
            date: new DateTimeImmutable(datetime: $this->dateField->getRawValue()),
            title: $this->titleField->getRawValue(),
            teaser: $this->teaserField->getRawValue(),
            text: $this->textField->getRawValue(),
            type: 1,
            archived: $this->archivedField->isChecked()
        );
        return true;
    }
}