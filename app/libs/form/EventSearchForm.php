<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\backend\libs\form\AbstractSearchForm;
use actra\backend\libs\form\component\SearchQueryField;
use actra\backend\libs\form\component\SearchSelectOptionsField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\db\DbClubRepository;
use app\settings\EventCategoryCollection;
use app\settings\EventCategoryEnum;

class EventSearchForm extends AbstractSearchForm
{
    public readonly int $clubID;
    public readonly ?EventCategoryEnum $eventCategoryEnum;
    public readonly string $searchQuery;
    private readonly SearchSelectOptionsField $clubField;
    private readonly SearchSelectOptionsField $categoryField;
    private readonly SearchQueryField $searchQueryField;

    public function __construct(int $memberID)
    {
        parent::__construct(name: 'EventSearchForm');
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
        $this->addField(
            formField: $this->clubField = new SearchSelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Verein'),
                formOptions: DbClubRepository::listMemberClubs(memberID: $memberID)->getFormOptions(),
                initialValue: ''
            )
        );
        $this->clubID = (int)$this->validateSearchField(searchField: $this->clubField);
        $this->addField(
            formField: $this->categoryField = new SearchSelectOptionsField(
                name: 'categoryField',
                label: HtmlText::encoded(textContent: 'Typ'),
                formOptions: EventCategoryCollection::createFullList()->getFormOptions(),
                initialValue: ''
            )
        );
        $this->eventCategoryEnum = EventCategoryEnum::tryFrom(
            value: $this->validateSearchField(searchField: $this->categoryField)
        );

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