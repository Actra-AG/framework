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
use actra\yuf\form\FormOptions;
use actra\yuf\html\HtmlText;
use app\libs\db\DbClubRepository;

class MemberSearchForm extends AbstractSearchForm
{
    public readonly int $clubID;
    public readonly string $status;
    public readonly string $searchQuery;
    private readonly SearchSelectOptionsField $clubField;
    private readonly SearchSelectOptionsField $statusField;
    private readonly SearchQueryField $searchQueryField;

    public function __construct()
    {
        parent::__construct(name: 'MemberSearchForm');
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
        $this->addField(
            formField: $this->clubField = new SearchSelectOptionsField(
                name: 'clubField',
                label: HtmlText::encoded(textContent: 'Vereinszugriff'),
                formOptions: DbClubRepository::listAll()->getFormOptions(),
                initialValue: ''
            )
        );
        $this->clubID = (int)$this->validateSearchField(searchField: $this->clubField);

        $formOptions = new FormOptions();
        $formOptions->addItem(
            key: 'toCheck',
            htmlText: HtmlText::encoded(textContent: 'Zu prüfen'),
        );
        $formOptions->addItem(
            key: 'approved',
            htmlText: HtmlText::encoded(textContent: 'angenommen'),
        );
        $formOptions->addItem(
            key: 'rejected',
            htmlText: HtmlText::encoded(textContent: 'abgelehnt'),
        );
        $this->addField(
            formField: $this->statusField = new SearchSelectOptionsField(
                name: 'statusField',
                label: HtmlText::encoded(textContent: 'Status'),
                formOptions: $formOptions,
                initialValue: ''
            )
        );
        $this->status = $this->validateSearchField(searchField: $this->statusField);

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