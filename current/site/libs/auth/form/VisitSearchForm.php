<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\form;

use framework\auth\AuthResult;
use framework\form\component\FormControl;
use framework\form\FormOptions;
use framework\html\HtmlText;
use site\libs\form\AbstractSearchForm;
use site\libs\form\component\SearchQueryField;
use site\libs\form\component\SearchSelectOptionsField;

class VisitSearchForm extends AbstractSearchForm
{
    public readonly int $status;
    public readonly string $searchQuery;
    private readonly SearchSelectOptionsField $statusFilterField;
    private readonly SearchQueryField $searchQueryField;

    public function __construct(string $name)
    {
        parent::__construct(name: $name);
        $this->addCssClass(className: 'form-filter');
        $this->addCssClass(className: 'form-autosubmit');
        $statusFilterOptions = new FormOptions();
        foreach (AuthResult::cases() as $authResult) {
            if ($authResult === AuthResult::UNDEFINED) {
                continue;
            }
            $statusFilterOptions->addItem(
                key: 'option_' . $authResult->value,
                htmlText: HtmlText::encoded(
                    textContent: $authResult->render()
                )
            );
        }
        $statusFilterOptions->addItem(key: 'option_6', htmlText: HtmlText::encoded(textContent: 'Kein Zugriff'));
        $statusFilterOptions->addItem(
            key: 'option_9',
            htmlText: HtmlText::encoded(textContent: 'Unbestätigter Zugang')
        );
        $this->addField(
            formField: $this->statusFilterField = new SearchSelectOptionsField(
                name: 'statusFilterField',
                label: HtmlText::encoded(textContent: 'Status'),
                formOptions: $statusFilterOptions,
                initialValue: '',
                individualEmptyValueLabel: HtmlText::encoded(textContent: 'alle')
            )
        );
        $this->status = (int)$this->validateSearchField(searchField: $this->statusFilterField);

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