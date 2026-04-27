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
use actra\yuf\table\column\DefaultColumn;
use actra\yuf\table\TableItemModel;
use app\libs\db\DbPageRepository;
use app\view\backend\php\pageVersion;
use DateTimeImmutable;

class PageHistoryTable extends AbstractTable
{
    public function __construct(string $pageName)
    {
        $dbQuery = DbPageRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: '(page.seite=? OR page.seite=?)',
            parameters: [
                $pageName,
                str_replace(
                    search: '.html',
                    replace: '',
                    subject: $pageName
                ),
            ]
        );
        parent::__construct(
            identifier: 'PageHistoryTable-' . base64_encode(string: $pageName),
            db: DB::get(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'ID',
                label: 'Datum',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . pageVersion::getPath(
                            ID: $tableItemModel->getRawValue(name: 'ID')
                        ) . '">' . new DateTimeImmutable(datetime: $tableItemModel->renderValue(name: 'date'))->format(
                            format: 'd.m.Y H:i:s'
                        ) . '</a>';
                },
                sortAscendingByDefault: false
            ),
            isDefaultSortColumn: true
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'fullName',
                label: 'Bearbeiter'
            )
        );
    }
}