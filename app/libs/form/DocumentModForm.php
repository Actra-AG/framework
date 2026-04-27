<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbDocument;
use app\libs\db\DbDocumentRepository;
use app\view\backend\php\event;

class DocumentModForm extends Form
{
    private readonly TextField $titleField;

    public function __construct(private readonly DbDocument $dbDocument)
    {
        parent::__construct(name: 'DocumentModForm-' . $dbDocument->ID);
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel'),
                value: $dbDocument->title,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel ein.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: event::getPath(ID: $this->dbDocument->eventID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        DbDocumentRepository::update(
            ID: $this->dbDocument->ID,
            title: $this->titleField->getRawValue()
        );
        return true;
    }
}