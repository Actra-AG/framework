<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\table\filter;

use framework\Core;
use framework\core\HttpRequest;
use framework\html\HtmlDataObjectCollection;
use framework\html\HtmlReplacementCollection;
use framework\html\HtmlSnippet;
use framework\security\CsrfToken;
use framework\table\table\DbResultTable;
use LogicException;

class TableFilter
{
    private const string sessionDataType = 'tableFilter';
    /** @var TableFilter[] */
    private static array $instances = [];

    private(set) bool $filtersApplied = false;
    /** @var AbstractTableFilterField[] $allFilterFields */
    private(set) array $allFilterFields = [];
    /** @var AbstractTableFilterField[] $primaryFields */
    private array $primaryFields = [];
    /** @var AbstractTableFilterField[] $secondaryFields */
    private array $secondaryFields = [];

    public function __construct(
        public readonly string $identifier,
        private readonly bool $showLegend = true,
        private readonly string $resetParameter = 'reset',
        private readonly ?string $individualHtmlSnippetPath = null,
        private readonly string $submitButtonLabel = 'Filter anwenden',
        private readonly string $resetLinkLabel = 'Filter zurücksetzen'
    ) {
        if (array_key_exists(key: $identifier, array: TableFilter::$instances)) {
            throw new LogicException(message: 'There is already a filter with the same identifier ' . $identifier);
        }
        TableFilter::$instances[$identifier] = $this;
    }

    public function validate(DbResultTable $dbResultTable): void
    {
        if (!is_null(value: HttpRequest::getInputString(keyName: $this->resetParameter))) {
            $this->reset(dbResultTable: $dbResultTable);
        }
        if (!is_null(value: HttpRequest::getInputString(keyName: $this->identifier))) {
            $this->reset(dbResultTable: $dbResultTable);
            $this->checkInput();
        }
        $this->filtersApplied = $this->applyFilters(dbResultTable: $dbResultTable);
    }

    protected function reset(DbResultTable $dbResultTable): void
    {
        foreach ($this->allFilterFields as $abstractTableFilterField) {
            $abstractTableFilterField->reset();
        }
        $dbResultTable->setCurrentPaginationPage(page: 1);
    }

    protected function checkInput(): void
    {
        foreach ($this->allFilterFields as $abstractTableFilterField) {
            $abstractTableFilterField->checkInput();
        }
    }

    protected function applyFilters(DbResultTable $dbResultTable): bool
    {
        $whereConditions = [];
        $parameters = [];
        foreach ($this->allFilterFields as $abstractTableFilterField) {
            if (!$abstractTableFilterField->isSelected()) {
                continue;
            }
            $dbQueryData = $abstractTableFilterField->getWhereCondition();
            $whereConditions[] = $dbQueryData->query;
            foreach ($dbQueryData->params as $sqlParameter) {
                $parameters[] = $sqlParameter;
            }
        }
        if (count(value: $whereConditions) === 0) {
            return false;
        }

        $this->addWhereConditionsToSelectQuery(
            dbResultTable: $dbResultTable,
            whereConds: $whereConditions,
            params: $parameters
        );

        return true;
    }

    private function addWhereConditionsToSelectQuery(
        DbResultTable $dbResultTable,
        array $whereConds,
        array $params
    ): void {
        foreach ($whereConds as $key => $val) {
            $whereConds[$key] = '(' . $val . ')';
        }

        $dbResultTable->dbQuery->addWherePart(
            wherePart: implode(separator: ' AND ', array: $whereConds),
            parameters: $params
        );
    }

    public function addPrimaryField(AbstractTableFilterField $abstractTableFilterField): void
    {
        $abstractTableFilterField->init();
        $this->primaryFields[] = $abstractTableFilterField;
        $this->allFilterFields[$abstractTableFilterField->identifier] = $abstractTableFilterField;
    }

    public function addSecondaryField(AbstractTableFilterField $abstractTableFilterField): void
    {
        $abstractTableFilterField->init();
        $this->secondaryFields[] = $abstractTableFilterField;
        $this->allFilterFields[$abstractTableFilterField->identifier] = $abstractTableFilterField;
    }

    public function render(): string
    {
        $replacements = new HtmlReplacementCollection();
        $replacements->addBool(identifier: 'showLegend', booleanValue: $this->showLegend);
        $replacements->addEncodedText(
            identifier: 'formAction',
            content: '?' . $this->identifier . '&' . DbResultTable::PARAM_FIND
        );
        $replacements->addEncodedText(identifier: 'csrfField', content: CsrfToken::renderAsHiddenPostField());
        $primaryFields = new HtmlDataObjectCollection();
        foreach ($this->primaryFields as $abstractTableFilterField) {
            $primaryFields->add(htmlDataObject: $abstractTableFilterField->render());
        }
        $replacements->addHtmlDataObjectCollection(
            identifier: 'primaryFields',
            htmlDataObjectCollection: $primaryFields
        );
        if (count(value: $this->secondaryFields) > 0) {
            $secondaryFields = new HtmlDataObjectCollection();
            $isSecondaryFilterTriggered = false;
            foreach ($this->secondaryFields as $abstractTableFilterField) {
                $secondaryFields->add(htmlDataObject: $abstractTableFilterField->render());
                if ($abstractTableFilterField->isSelected()) {
                    $isSecondaryFilterTriggered = true;
                }
            }
            $replacements->addBool(identifier: 'hasSecondaryFilters', booleanValue: true);
            $replacements->addBool(identifier: 'isSecondaryFilterTriggered', booleanValue: $isSecondaryFilterTriggered);
            $replacements->addHtmlDataObjectCollection(
                identifier: 'secondaryFields',
                htmlDataObjectCollection: $secondaryFields
            );
        } else {
            $replacements->addBool(identifier: 'hasSecondaryFilters', booleanValue: false);
        }
        $replacements->addEncodedText(identifier: 'resetHref', content: '?' . $this->resetParameter);
        $replacements->addEncodedText(identifier: 'submitButtonLabel', content: $this->submitButtonLabel);
        $replacements->addEncodedText(identifier: 'resetLinkLabel', content: $this->resetLinkLabel);

        $individualHtmlSnippetPath = $this->individualHtmlSnippetPath;

        return new HtmlSnippet(
            htmlSnippetFilePath: is_null(value: $individualHtmlSnippetPath) ? Core::get(
                )->frameworkDirectory . 'table' . DIRECTORY_SEPARATOR . 'filter' . DIRECTORY_SEPARATOR . 'tableFilter.html' : $individualHtmlSnippetPath,
            replacements: $replacements,
        )->render();
    }

    protected function getFromSession(string $index): ?string
    {
        return DbResultTable::getFromSession(
            dataType: TableFilter::sessionDataType,
            identifier: $this->identifier,
            index: $index
        );
    }

    protected function saveToSession(string $index, string $value): void
    {
        DbResultTable::saveToSession(
            dataType: TableFilter::sessionDataType,
            identifier: $this->identifier,
            index: $index,
            value: $value
        );
    }
}