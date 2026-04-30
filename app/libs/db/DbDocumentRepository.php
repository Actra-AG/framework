<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;
use actra\yuf\db\DbQuery;
use stdClass;

class DbDocumentRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
          query: '
                SELECT document.ID,
                       document.dateiname AS fileName,
                       document.titel as title,
                       document.objektID as eventID,
                       format.extension
                FROM dokumente document
                    INNER JOIN dateiformate format
                        ON document.type=format.mimetype
            '
        );
    }

    private static function createItem(stdClass $item): DbDocument
    {
        return new DbDocument(
          ID: $item->ID,
          eventID: $item->eventID,
          title: $item->title,
          fileName: $item->fileName,
          extension: $item->extension
        );
    }

    public static function select(DbQuery $dbQuery): DbDocumentCollection
    {
        $dbDocumentCollection = new DbDocumentCollection();
        foreach (
          $dbQuery->selectFromDb(
            db: DB::get(),
            offset: 0,
            rowCount: 1000
          ) as $item
        ) {
            $dbDocumentCollection->add(
              dbDocument: DbDocumentRepository::createItem(item: $item)
            );
        }

        return $dbDocumentCollection;
    }

    public static function selectByID(int $ID): ?DbDocument
    {
        $dbQuery = DbDocumentRepository::getDbQuery();
        $dbQuery->addWherePart(
          wherePart: 'document.ID=?',
          parameters: [$ID]
        );
        $dbDocumentCollection = DbDocumentRepository::select(dbQuery: $dbQuery);

        return $dbDocumentCollection->isEmpty() ? null : $dbDocumentCollection->first();
    }

    public static function insert(
      int $eventID,
      string $title,
      string $fileName,
      string $mimeType
    ): int {
        $db = DB::get();
        $db->execute(
          sql: '
                INSERT INTO dokumente
                SET objekt=?,
                    objektID=?,
                    titel=?,
                    dateiname=?,
                    type=?,
                    views=?
            ',
          parameters: [
            'anlass',
            $eventID,
            $title,
            $fileName,
            $mimeType,
            0,
          ]
        );
        return $db->lastInsertId();
    }

    public static function update(
      int $ID,
      string $title
    ): void {
        DB::get()->execute(
          sql: '
                UPDATE dokumente
                SET titel=?
                WHERE ID=?
            ',
          parameters: [
            $title,
            $ID,
          ]
        );
    }

    public static function increaseViews(int $ID): void
    {
        DB::get()->execute(
          sql: '
                UPDATE dokumente
                SET views=views+1
                WHERE ID=?
            ',
          parameters: [
            $ID,
          ]
        );
    }

    public static function delete(int $ID): void
    {
        DB::get()->execute(
          sql: '
                DELETE FROM dokumente
                WHERE ID=?
            ',
          parameters: [
            $ID,
          ]
        );
    }

    public static function getDbQueryForEventDocuments(int $eventID): DbQuery
    {
        $dbQuery = DbDocumentRepository::getDbQuery();
        $dbQuery->addWherePart(
          wherePart: 'document.objekt=? AND objektID=?',
          parameters: [
            'anlass',
            $eventID,
          ]
        );
        $dbQuery->addOrderPart(column: 'document.titel');

        return $dbQuery;
    }
}