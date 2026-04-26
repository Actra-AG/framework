<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\table;

use actra\backend\libs\db\DB;
use actra\backend\libs\table\AbstractTable;
use actra\yuf\table\column\ActionsColumn;
use actra\yuf\table\column\BooleanColumn;
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\column\DateColumn;
use actra\yuf\table\column\DefaultColumn;
use actra\yuf\table\TableItemModel;
use app\libs\db\DbNewsRepository;
use app\libs\form\NewsSearchForm;
use app\view\backend\php\news;
use app\view\backend\php\newsMod;

class NewsTable extends AbstractTable
{
    public function __construct(
        NewsSearchForm $newsSearchForm,
        int $type
    ) {
        $dbQuery = DbNewsRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'news.typ=?',
            parameters: [$type]
        );
        if ($newsSearchForm->archived !== null) {
            $dbQuery->addWherePart(
                wherePart: 'news.archiv=?',
                parameters: [$newsSearchForm->archived ? 1 : 0]
            );
        }
        $searchQuery = $newsSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $newsSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'news.titel news.teaser news.text',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: 'NewsTable',
            db: DB::get(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: $dateColumn = new DateColumn(
                identifier: 'date',
                label: 'Datum',
                sortAscendingByDefault: false
            ),
            isDefaultSortColumn: true
        );
        $dateColumn->format = 'd.m.Y';
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'title',
                label: 'Titel'
            )
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'author',
                label: 'Erfasser',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return trim(
                        string: $tableItemModel->renderValue(
                            name: 'firstName'
                        ) . ' ' . $tableItemModel->renderValue(name: 'lastName')
                    );
                }
            )
        );
        $this->addColumn(
            abstractTableColumn: new BooleanColumn(
                identifier: 'isArchived',
                label: 'Archiviert'
            )
        );
        $actionColumn = new ActionsColumn();
        $actionColumn->addEditActionLink(linkTarget: newsMod::getPath(ID: '[ID]'));
        $actionColumn->addDeleteLink(linkTarget: news::getPath() . '?' . news::PARAM_REMOVE . '=[ID]');
        $this->addColumn(abstractTableColumn: $actionColumn);
    }
}