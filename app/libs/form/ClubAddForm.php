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
use app\libs\db\DbClubRepository;
use app\view\backend\php\clubs;

class ClubAddForm extends Form
{
    private readonly TextField $nameField;

    public function __construct()
    {
        parent::__construct(name: 'ClubAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->nameField = new TextField(
                name: 'nameField',
                label: HtmlText::encoded(textContent: 'Name'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Namen ein.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: clubs::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        DbClubRepository::insert(
            name: $this->nameField->getRawValue()
        );
        return true;
    }
}