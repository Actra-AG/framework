<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;
use actra\yuf\db\DbQuery;
use DateTimeImmutable;
use stdClass;

class DbEventRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT event.ID,
                       event.titel AS title,
                       event.registered,
                       event.datumVon AS dateFrom,
                       event.datumBis AS dateTo,
                       event.ort AS location,
                       event.zeit AS time,
                       CONCAT(event.datumVon, event.datumBis, event.zeit) AS defaultOrdering
                FROM jahresprogramm event
            '
        );
    }

    public static function select(DbQuery $dbQuery): DbEventCollection
    {
        $dbEventCollection = new DbEventCollection();
        foreach (
            $dbQuery->selectFromDb(
                db: DB::get(),
                offset: 0,
                rowCount: 1000
            ) as $item
        ) {
            $dbEventCollection->add(
                dbEvent: DbEventRepository::createItem(item: $item)
            );
        }

        return $dbEventCollection;
    }

    public static function listToCheck(): DbEventCollection
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'event.confirmed IS NULL AND event.denied IS NULL',
            parameters: []
        );
        $dbQuery->addOrderPart(
            column: 'event.registered',
            ascending: false
        );
        return DbEventRepository::select(dbQuery: $dbQuery);
    }

    private static function createItem(stdClass $item): DbEvent
    {
        return new DbEvent(
            ID: $item->ID,
            title: $item->title,
            registered: new DateTimeImmutable(datetime: $item->registered),
            dateFrom: new DateTimeImmutable(datetime: $item->dateFrom),
            dateTo: new DateTimeImmutable(datetime: $item->dateTo)
        );
    }

    public static function getBoardEventQuery(): DbQuery
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'event.vorstand=1',
            parameters: []
        );
        $dbQuery->addWherePart(
            wherePart: 'event.confirmed IS NOT NULL',
            parameters: []
        );
        return $dbQuery;
    }
}