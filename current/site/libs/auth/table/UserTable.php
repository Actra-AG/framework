<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland - www.actra.ch
 */

namespace site\libs\auth\table;

use framework\db\DbQuery;
use framework\table\column\ActionsColumn;
use framework\table\column\BooleanColumn;
use framework\table\column\DateColumn;
use framework\table\column\DefaultColumn;
use site\libs\auth\form\UserSearchForm;
use site\libs\db\DB;
use site\libs\table\AbstractTable;
use site\view\backend\php\user;

class UserTable extends AbstractTable
{
    public function __construct(UserSearchForm $userSearchForm)
    {
        $dbQuery = DbQuery::createFromSqlQuery(
            query: '
					SELECT auth_user.ID,
					       auth_user.firstName,
					       auth_user.lastName,
					       auth_user.email,
					       auth_user.active,
					       (SELECT GROUP_CONCAT(auth_group.title SEPARATOR \'<br>\') FROM auth_group WHERE auth_group.ID IN (SELECT groupID FROM auth_user_group WHERE userID=auth_user.ID)) AS rightGroups,
					       auth_user.registered,
					       auth_user.invited
					FROM auth_user
				'
        );
        $dbAuthGroupItem = $userSearchForm->dbAuthGroupItem;
        if (!is_null(value: $dbAuthGroupItem)) {
            $dbQuery->addWherePart(
                wherePart: 'auth_user.ID IN (SELECT userID FROM auth_user_group WHERE groupID=?)',
                parameters: [
                    $dbAuthGroupItem->ID,
                ]
            );
        }
        $searchQuery = $userSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $userSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'auth_user.firstName auth_user.lastName auth_user.email',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: 'UserTable',
            db: DB::getHAAS(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'ID',
                label: 'ID',
                isSortable: true,
                sortAscendingByDefault: false
            ),
            isDefaultSortColumn: true
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'firstName',
                label: 'Vorname',
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'lastName',
                label: 'Nachname',
                isSortable: true
            )
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
                identifier: 'active',
                label: 'Aktiv',
                isSortable: true,
                sortAscendingByDefault: false
            )
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'rightGroups',
                label: 'Rechtegruppe(n)',
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: $registeredColumn = new DateColumn(
                identifier: 'registered',
                label: 'erfasst',
                isSortable: true
            )
        );
        $registeredColumn->format = 'd.m.Y';
        $this->addColumn(
            abstractTableColumn: $invitedColumn = new DateColumn(
                identifier: 'invited',
                label: 'eingeladen',
                isSortable: true
            )
        );
        $invitedColumn->format = 'd.m.Y';
        $this->addColumn(abstractTableColumn: $detailsColumn = new ActionsColumn(label: 'Details'));
        $detailsColumn->addCellCssClass(className: 'show');
        $detailsColumn->addIndividualActionLink(
            identifier: 'details',
            linkHTML: '<a class="details" href="' . user::getPath(ID: '[ID]') . '">anzeigen</a>'
        );
    }
}