<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\table;

use actra\yuf\table\column\ActionsColumn;
use actra\yuf\table\column\CallbackColumn;
use actra\yuf\table\renderer\TableHeadRenderer;
use actra\yuf\table\table\SmartTable;
use actra\yuf\table\TableItemCollection;
use actra\yuf\table\TableItemModel;
use app\libs\common\Helper;
use app\view\backend\php\pageHistory;
use app\view\backend\php\pageMod;
use app\view\backend\php\pages;

class PagesTable extends SmartTable
{
    public function __construct()
    {
        parent::__construct(
            identifier: 'PagesTable',
            tableHeadRenderer: new TableHeadRenderer(),
            tableItemCollection: new TableItemCollection()
        );
        foreach (Helper::listFrontendPages() as $file) {
            $this->addDataItem(
                tableItemModel: new TableItemModel(
                    dataObject: (object)[
                        'file' => $file,
                        'base64' => base64_encode(string: $file)
                    ]
                )
            );
        }
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'file',
                label: 'Datei',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . pageMod::getPath(
                            fileName: $tableItemModel->renderValue(name: 'base64')
                        ) . '">' . $tableItemModel->renderValue(name: 'file') . '</a>';
                }
            )
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'archive',
                label: 'Archiv',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return '<a href="' . pageHistory::getPath(
                            fileName: $tableItemModel->renderValue(name: 'base64')
                        ) . '">anzeigen</a>';
                }
            )
        );
        $this->addColumn(abstractTableColumn: $detailsColumn = new ActionsColumn(label: ''));
        $detailsColumn->addDeleteLink(
            linkTarget: pages::getPath() . '?' . pages::PARAM_REMOVE . '=[base64]'
        );
    }
}