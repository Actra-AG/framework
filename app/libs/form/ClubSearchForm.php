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

class ClubSearchForm extends AbstractSearchForm
{
    public readonly string $searchQuery;
    private readonly SearchQueryField $searchQueryField;

    public function __construct()
    {
        parent::__construct(name: 'ClubSearchForm');
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
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