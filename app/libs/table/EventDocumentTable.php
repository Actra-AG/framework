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
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\TableItemModel;
use app\libs\common\Helper;
use app\libs\db\DbDocumentRepository;
use app\view\backend\php\documentMod;
use app\view\backend\php\event;

class EventDocumentTable extends AbstractTable
{
    public function __construct(
        int $eventID,
        bool $userCanEdit
    ) {
        parent::__construct(
            identifier: 'EventDocumentTable-' . $eventID,
            db: DB::get(),
            dbQuery: DbDocumentRepository::getDbQueryForEventDocuments(eventID: $eventID),
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'title',
                label: 'Dokument',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="/' . implode(
                            separator: '/',
                            array: [
                                'dokumente',
                                $tableItemModel->renderValue(name: 'ID'),
                                Helper::createDocumentToken(documentID: $tableItemModel->getRawValue(name: 'ID')),
                                urlencode(string: $tableItemModel->renderValue(name: 'fileName'))
                            ]
                        ) . '">' . $tableItemModel->renderValue(
                            name: 'title'
                        ) . '</a> (' . $tableItemModel->renderValue(
                            name: 'fileName'
                        ) . ')';
                }
            ),
            isDefaultSortColumn: true
        );
        if ($userCanEdit) {
            $actionsColumn = new ActionsColumn();
            $actionsColumn->addEditActionLink(
                linkTarget: documentMod::getPath(ID: '[ID]')
            );
            $actionsColumn->addDeleteLink(
                linkTarget: event::getPath(ID: '[eventID]') . '?' . event::REMOVE_DOCUMENT . '=[ID]'
            );
            $this->addColumn(abstractTableColumn: $actionsColumn);
        }
    }
}