<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\auth\MyAuthUser;
use actra\backend\libs\db\DB;
use actra\yuf\db\DbQuery;
use app\settings\EventCategoryCollection;
use app\settings\EventCategoryEnum;
use DateTimeImmutable;
use stdClass;

class DbEventRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT event.ID,
                       event.registered,
                       event.lastmod as lastModified,
                       event.confirmed,
                       event.denied,
                       event.registered_by,
                       event.vereinID AS clubID,
                       event.datumVon AS dateFrom,
                       event.datumBis AS dateTo,
                       event.zeit AS time,
                       event.titel AS title,
                       event.ort AS location,
                       event.bemerkungen AS notes,
                       event.export AS export,
                       event.zeitVon AS timeFrom,
                       event.zeitBis AS timeTo,
                       CONCAT(event.datumVon, event.datumBis, event.zeit) AS defaultOrdering,
                       club.name AS clubName,
                       (SELECT GROUP_CONCAT(eventCategory.categoryName) FROM eventCategory WHERE eventCategory.eventID=event.ID) AS eventCategories,
                       auth_user.firstName,
                       auth_user.lastName,
                       auth_user.email AS registeredByEmail
                FROM jahresprogramm event
                    LEFT JOIN vereine club ON event.vereinID=club.ID
                    LEFT JOIN auth_user ON event.registered_by=auth_user.ID
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
            registered: new DateTimeImmutable(datetime: $item->registered),
            lastModified: new DateTimeImmutable(datetime: $item->lastModified),
            confirmed: $item->confirmed === null ? null : new DateTimeImmutable(datetime: $item->confirmed),
            denied: $item->denied === null ? null : new DateTimeImmutable(datetime: $item->denied),
            registeredByUserID: $item->registered_by,
            registeredByName: $item->firstName . ' ' . $item->lastName,
            registeredByEmail: $item->registeredByEmail,
            clubID: $item->clubID,
            clubName: $item->clubName,
            dateFrom: new DateTimeImmutable(datetime: $item->dateFrom),
            dateTo: new DateTimeImmutable(datetime: $item->dateTo),
            timeFormatted: $item->time,
            title: $item->title,
            location: $item->location,
            notes: $item->notes,
            export: $item->export === 1,
            timeFrom: new DateTimeImmutable(datetime: $item->dateFrom . ' ' . $item->timeFrom),
            timeTo: new DateTimeImmutable(datetime: $item->dateTo . ' ' . $item->timeTo),
            eventCategoryCollection: EventCategoryCollection::createFromRawList(
                rawList: (string)$item->eventCategories
            )
        );
    }

    public static function getBoardEventQuery(): DbQuery
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'event.ID IN (SELECT eventID FROM eventCategory WHERE categoryName=?)',
            parameters: [
                EventCategoryEnum::VORSTAND->value,
            ]
        );
        $dbQuery->addWherePart(
            wherePart: 'event.confirmed IS NOT NULL',
            parameters: []
        );
        return $dbQuery;
    }

    public static function getMemberEventQuery(int $memberID): DbQuery
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'event.registered_by=?',
            parameters: [$memberID]
        );

        return $dbQuery;
    }

    public static function selectByID(int $ID): ?DbEvent
    {
        $dbQuery = DbEventRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'event.ID=?',
            parameters: [$ID]
        );
        $dbEventCollection = DbEventRepository::select(dbQuery: $dbQuery);

        return $dbEventCollection->isEmpty() ? null : $dbEventCollection->first();
    }

    public static function deny(int $ID): void
    {
        DB::get()->execute(
            sql: '
                UPDATE jahresprogramm
                SET denied=NOW(),
                    confirmed=NULL
                WHERE ID=?
            ',
            parameters: [
                $ID
            ]
        );
    }

    public static function confirm(int $ID): void
    {
        DB::get()->execute(
            sql: '
                UPDATE jahresprogramm
                SET confirmed=NOW(),
                    denied=NULL
                WHERE ID=?
            ',
            parameters: [
                $ID,
            ]
        );
    }

    public static function insert(
        MyAuthUser $myAuthUser,
        int $clubID,
        DateTimeImmutable $dateFrom,
        DateTimeImmutable $dateTo,
        string $time,
        string $title,
        string $location,
        string $notes,
        bool $export,
        DateTimeImmutable $timeFrom,
        DateTimeImmutable $timeTo,
    ): int {
        $db = DB::get();
        $db->execute(
            sql: '
                INSERT INTO jahresprogramm
                SET lastmod=NOW(),
                    registered_by=?,
                    vereinID=?,
                    datumVon=?,
                    datumBis=?,
                    zeit=?,
                    titel=?,
                    ort=?,
                    bemerkungen=?,
                    export=?,
                    zeitVon=?,
                    zeitBis=?
            ',
            parameters: [
                $myAuthUser->ID,
                $clubID,
                $dateFrom->format(format: 'Y-m-d'),
                $dateTo->format(format: 'Y-m-d'),
                $time,
                $title,
                $location,
                $notes,
                $export ? 1 : 0,
                $timeFrom->format(format: 'H:i'),
                $timeTo->format(format: 'H:i'),
            ]
        );
        return $db->lastInsertId();
    }

    public static function update(
        int $ID,
        int $clubID,
        DateTimeImmutable $dateFrom,
        DateTimeImmutable $dateTo,
        string $time,
        string $title,
        string $location,
        string $notes,
        bool $export,
        DateTimeImmutable $timeFrom,
        DateTimeImmutable $timeTo,
    ): void {
        DB::get()->execute(
            sql: '
                UPDATE jahresprogramm
                SET lastmod=NOW(),
                    vereinID=?,
                    datumVon=?,
                    datumBis=?,
                    zeit=?,
                    titel=?,
                    ort=?,
                    bemerkungen=?,
                    export=?,
                    zeitVon=?,
                    zeitBis=?
                WHERE ID=?
            ',
            parameters: [
                $clubID,
                $dateFrom->format(format: 'Y-m-d'),
                $dateTo->format(format: 'Y-m-d'),
                $time,
                $title,
                $location,
                $notes,
                $export ? 1 : 0,
                $timeFrom->format(format: 'H:i'),
                $timeTo->format(format: 'H:i'),
                $ID,
            ]
        );
    }

    public static function delete(int $ID): void
    {
        DB::get()->execute(
            sql: '
                DELETE FROM jahresprogramm
                WHERE ID=?
            ',
            parameters: [
                $ID,
            ]
        );
    }
}