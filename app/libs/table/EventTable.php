<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\table;

use actra\backend\libs\auth\MyAuthUser;
use actra\backend\libs\db\DB;
use actra\backend\libs\table\AbstractTable;
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\column\DateColumn;
use actra\yuf\table\column\DefaultColumn;
use actra\yuf\table\TableItemModel;
use app\libs\backend\AuthUserHelper;
use app\libs\db\DbEventRepository;
use app\libs\form\EventSearchForm;
use app\view\backend\php\event;

class EventTable extends AbstractTable
{
    public function __construct(EventSearchForm $eventSearchForm)
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $myAuthUser = MyAuthUser::get();
        if (!AuthUserHelper::isAdmin()) {
            $dbQuery->addWherePart(
                wherePart: 'event.registered_by=?',
                parameters: [$myAuthUser->ID]
            );
        }
        if ($eventSearchForm->clubID > 0) {
            $dbQuery->addWherePart(
                wherePart: 'event.vereinID=?',
                parameters: [$eventSearchForm->clubID]
            );
        }
        if ($eventSearchForm->eventCategoryEnum !== null) {
            $dbQuery->addWherePart(
                wherePart: 'event.ID IN (SELECT eventID FROM eventCategory WHERE categoryName=?)',
                parameters: [
                    $eventSearchForm->eventCategoryEnum->value,
                ]
            );
        }
        $searchQuery = $eventSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $eventSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'event.titel event.ort event.bemerkungen',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: 'EventTable',
            db: DB::get(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'defaultOrdering',
                label: '#',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return $tableItemModel->renderValue(name: 'ID');
                }
            ),
            isDefaultSortColumn: true
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'title',
                label: 'Titel',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . event::getPath(
                            ID: $tableItemModel->getRawValue(name: 'ID')
                        ) . '">' . $tableItemModel->renderValue(name: 'title') . '</a>';
                }
            )
        );
        $this->addColumn(
            abstractTableColumn: $dateFromColumn = new DateColumn(
                identifier: 'dateFrom',
                label: 'Von'
            )
        );
        $dateFromColumn->format = 'd.m.Y';
        $this->addColumn(
            abstractTableColumn: $dateToColumn = new DateColumn(
                identifier: 'dateTo',
                label: 'Bis'
            )
        );
        $dateToColumn->format = 'd.m.Y';
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'time',
                label: 'Zeit'
            )
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'location',
                label: 'Ort'
            )
        );
    }
}