<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\table;

use actra\backend\libs\db\DB;
use actra\backend\libs\table\AbstractTable;
use actra\yuf\table\column\BooleanColumn;
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\column\DefaultColumn;
use actra\yuf\table\TableItemModel;
use app\libs\db\DbMemberRepository;
use app\libs\form\MemberSearchForm;
use app\view\backend\php\member;

class MemberTable extends AbstractTable
{
    public function __construct(MemberSearchForm $memberSearchForm)
    {
        $dbQuery = DbMemberRepository::getDbQuery();
        if ($memberSearchForm->clubID > 0) {
            $dbQuery->addWherePart(
                wherePart: 'member.ID IN(SELECT benutzerID FROM benutzervereine WHERE vereinID=?)',
                parameters: [$memberSearchForm->clubID]
            );
        }
        if ($memberSearchForm->status !== '') {
            $dbQuery->addWherePart(
                wherePart: match ($memberSearchForm->status) {
                    'toCheck' => 'member.accepted IS NULL AND member.denied IS NULL',
                    'approved' => 'member.accepted IS NOT NULL',
                    'rejected' => 'member.denied IS NOT NULL',
                },
                parameters: []
            );
        }
        $searchQuery = $memberSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $memberSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'firstName lastName email',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: 'MemberTable',
            db: DB::get(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'fullName',
                label: 'Name',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . member::getPath(
                            ID: $tableItemModel->getRawValue(name: 'ID')
                        ) . '">' . $tableItemModel->renderValue(name: 'fullName') . '</a>';
                },
                isSortable: true
            ),
            isDefaultSortColumn: true,
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'email',
                label: 'E-Mail',
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new BooleanColumn(
                identifier: 'honorary',
                label: 'Ehrenmitglied',
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'status',
                label: 'Status',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    if ($tableItemModel->getRawValue(name: 'accepted') !== null) {
                        return 'angenommen';
                    }
                    if ($tableItemModel->getRawValue(name: 'denied') !== null) {
                        return 'abgelehnt';
                    }
                    return 'zu&nbsp;prüfen';
                }
            )
        );
    }
}