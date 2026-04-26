<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\libs\form\AbstractSearchForm;
use actra\backend\libs\form\component\SearchQueryField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\form\component\YesNoSelectOptionsField;

class NewsSearchForm extends AbstractSearchForm
{
    public readonly ?bool $archived;
    public readonly string $searchQuery;
    private readonly YesNoSelectOptionsField $archivedField;
    private readonly SearchQueryField $searchQueryField;

    public function __construct()
    {
        parent::__construct(name: 'NewsSearchForm');
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
        $this->addField(
            formField: $this->archivedField = new YesNoSelectOptionsField(
                name: 'archived',
                label: HtmlText::encoded(textContent: 'Archiviert')
            )
        );
        $this->archived = match ($this->validateSearchField(searchField: $this->archivedField)) {
            'no' => false,
            'yes' => true,
            default => null
        };
        $this->addField(formField: $this->searchQueryField = new SearchQueryField());
        $this->searchQuery = $this->validateSearchField(searchField: $this->searchQueryField);
        $this->addComponent(
            formComponent: new FormControl(
                name: 'find',
                submitLabel: HtmlText::encoded(textContent: 'Anzeigen')
            )
        );
    }
}