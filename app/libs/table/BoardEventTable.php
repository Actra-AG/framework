<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\table;

use actra\backend\libs\db\DB;
use actra\backend\libs\table\AbstractTable;
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\column\DateColumn;
use actra\yuf\table\column\DefaultColumn;
use actra\yuf\table\TableItemModel;
use app\libs\db\DbEventRepository;
use app\view\backend\php\anlassDet;

class BoardEventTable extends AbstractTable
{
    public function __construct(int $selectedYear)
    {
        $dbQuery = DbEventRepository::getBoardEventQuery();
        $dbQuery->addWherePart(
            wherePart: 'YEAR(event.datumVon)<=? AND YEAR(event.datumBis)>=?',
            parameters: [
                $selectedYear,
                $selectedYear,
            ]
        );
        parent::__construct(
            identifier: 'UserTable',
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
            abstractTableColumn: new CallbackColumn(
                identifier: 'title',
                label: 'Titel',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . anlassDet::getPath(
                            ID: $tableItemModel->getRawValue(name: 'ID')
                        ) . '">' . $tableItemModel->renderValue(name: 'title') . '</a>';
                }
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