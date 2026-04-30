<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\common;

use actra\backend\libs\auth\UserController;
use actra\yuf\Core;
use actra\yuf\form\model\FileDataModel;
use app\libs\db\DbDocument;
use app\libs\db\DbDocumentRepository;
use app\libs\db\DbEvent;
use app\libs\db\DbEventCategoryRepository;
use app\libs\db\DbEventRepository;
use app\libs\db\DbMemberRepository;

class Helper
{
    public static function deleteMember(int $ID): void
    {
        DbMemberRepository::delete(ID: $ID);
        UserController::deleteUser(userID: $ID);
    }

    public static function getDocumentDirectory(): string
    {
        return Core::get()->baseDirectory . '/documents/';
    }

    public static function saveDocument(
      FileDataModel $fileDataModel,
      int $ID,
      string $extension
    ): void {
        $documentDirectory = Helper::getDocumentDirectory();
        if (!is_dir(filename: $documentDirectory)) {
            mkdir(directory: $documentDirectory);
        }
        rename(
          from: $fileDataModel->tmp_name,
          to: $documentDirectory . $ID . '.' . $extension
        );
    }

    public static function createDocumentToken(int $documentID): string
    {
        return md5(string: 'aasmdsjtk' . $documentID . 'asujdt3?nz34g');
    }

    public static function deleteDocument(DbDocument $dbDocument): void
    {
        $filePath = $dbDocument->getFilePath();
        if (file_exists(filename: $filePath)) {
            unlink(filename: $filePath);
        }
        DbDocumentRepository::delete(ID: $dbDocument->ID);
    }

    public static function deleteEvent(DbEvent $dbEvent): void
    {
        foreach ($dbEvent->eventCategoryCollection->list() as $eventCategoryEnum) {
            DbEventCategoryRepository::delete(
              eventID: $dbEvent->ID,
              eventCategoryEnum: $eventCategoryEnum
            );
        }
        foreach (
          DbDocumentRepository::select(
            dbQuery: DbDocumentRepository::getDbQueryForEventDocuments(
              eventID: $dbEvent->ID
            )
          )->list() as $dbDocument
        ) {
            Helper::deleteDocument(dbDocument: $dbDocument);
        }
        DbEventRepository::delete(ID: $dbEvent->ID);
    }

    public static function getFrontendHtmlPath(): string
    {
        return Core::get()->viewDirectory . 'frontend/html';
    }

    public static function getFrontendPhpPath(): string
    {
        return Core::get()->viewDirectory . 'frontend/php';
    }

    public static function listFrontendPages(): array
    {
        $files = [];
        foreach (scandir(directory: Helper::getFrontendHtmlPath()) as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $files[] = $file;
        }
        asort(
          array: $files,
          flags: SORT_NATURAL | SORT_FLAG_CASE
        );
        return $files;
    }

    public static function dynTableHeader($fArr, $orderby = '', $ox = ''): string
    {
        $th = "<tr>\n";
        foreach ($fArr as $key => $val) {
            $th .= "<th scope=\"col\" {$val['attributes']}>";
            if ($val['order'] == 1) {
                $nox = $val['ox'];
                if ($orderby == $key && $ox == $val['ox']) {
                    if ($val['ox'] == "ASC") {
                        $nox = "DESC";
                    } else {
                        $nox = "ASC";
                    }
                }
                $th .= "<a href=\"?orderby={$key}&amp;ox={$nox}\">{$val['value']}</a>";
            } else {
                $th .= $val['value'];
            }
            $th .= "</th>\n";
        }
        $th .= "</tr>\n";

        return $th;
    }
}