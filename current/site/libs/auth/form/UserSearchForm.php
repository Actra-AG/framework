<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\form;

use framework\form\component\FormControl;
use framework\html\HtmlText;
use site\libs\auth\db\DbAuthGroup;
use site\libs\auth\db\DbAuthGroupItem;
use site\libs\form\AbstractSearchForm;
use site\libs\form\component\SearchQueryField;
use site\libs\form\component\SearchSelectOptionsField;

class UserSearchForm extends AbstractSearchForm
{
    public readonly ?DbAuthGroupItem $dbAuthGroupItem;
    public readonly string $searchQuery;
    private readonly SearchSelectOptionsField $userGroupField;
    private readonly SearchQueryField $searchQueryField;

    public function __construct()
    {
        parent::__construct(name: 'UserSearchForm');
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
        $this->addField(
            formField: $this->userGroupField = new SearchSelectOptionsField(
                name: 'userGroup',
                label: HtmlText::encoded(textContent: 'Benutzergruppe'),
                formOptions: DbAuthGroup::listAll()->getFormOptions(),
                initialValue: '',
                individualEmptyValueLabel: HtmlText::encoded(textContent: 'alle')
            )
        );
        $this->dbAuthGroupItem = DbAuthGroup::selectByID(
            ID: (int)$this->validateSearchField(
                searchField: $this->userGroupField
            )
        );
        $this->addField(formField: $this->searchQueryField = new SearchQueryField());
        $this->searchQuery = $this->validateSearchField(searchField: $this->searchQueryField);
        $this->addComponent(
            formComponent: new FormControl(
                name: 'find',
                submitLabel: HtmlText::encoded(textContent: 'anzeigen')
            )
        );
    }
}