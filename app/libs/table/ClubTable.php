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
use actra\yuf\table\column\DefaultColumn;
use app\libs\db\DbClubRepository;
use app\libs\form\ClubSearchForm;
use app\view\backend\php\clubMod;
use app\view\backend\php\clubs;

class ClubTable extends AbstractTable
{
    public function __construct(ClubSearchForm $clubSearchForm)
    {
        $dbQuery = DbClubRepository::getDbQuery();
        $searchQuery = $clubSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $clubSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'club.name',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: 'ClubTable',
            db: DB::get(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'ID',
                label: '#',
                isSortable: true
            ),
            isDefaultSortColumn: true
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'name',
                label: 'Verein'
            )
        );
        $actionColumn = new ActionsColumn();
        $actionColumn->addEditActionLink(linkTarget: clubMod::getPath(ID: '[ID]'));
        $actionColumn->addDeleteLink(linkTarget: clubs::getPath() . '?' . clubs::PARAM_REMOVE . '=[ID]');
        $this->addColumn(abstractTableColumn: $actionColumn);
    }
}