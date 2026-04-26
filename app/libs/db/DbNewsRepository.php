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

class DbNewsRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT news.ID,
                       news.datum AS date,
                       news.titel AS title,
                       news.archiv AS isArchived,
                       news.registered_by,
                       news.teaser,
                       news.text,
                       news.typ AS type,
                       auth_user.firstName,
                       auth_user.lastName
                FROM news
                    LEFT JOIN auth_user ON news.registered_by=auth_user.ID
            '
        );
    }

    private static function createItem(stdClass $item): DbNews
    {
        return new DbNews(
            ID: $item->ID,
            registeredByUserID: $item->registered_by,
            date: new DateTimeImmutable(datetime: $item->date),
            title: $item->title,
            teaser: $item->teaser,
            htmlContent: $item->text,
            isArchive: $item->isArchived === 1,
            type: $item->type
        );
    }

    public static function select(DbQuery $dbQuery): DbNewsCollection
    {
        $dbNewsCollection = new DbNewsCollection();
        foreach (
            $dbQuery->selectFromDb(
                db: DB::get(),
                offset: 0,
                rowCount: 1000
            ) as $item
        ) {
            $dbNewsCollection->add(
                dbNews: DbNewsRepository::createItem(item: $item)
            );
        }

        return $dbNewsCollection;
    }

    public static function selectByID(int $ID): ?DbNews
    {
        $dbQuery = DbNewsRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'news.ID=?',
            parameters: [$ID]
        );
        $dbNewsCollection = DbNewsRepository::select(dbQuery: $dbQuery);

        return $dbNewsCollection->isEmpty() ? null : $dbNewsCollection->first();
    }

    public static function listForStartPage(): DbNewsCollection
    {
        $dbQuery = DbNewsRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'news.archiv=0 AND news.typ=1',
            parameters: []
        );
        $dbQuery->addOrderPart(
            column: 'news.datum',
            ascending: false
        );

        return DbNewsRepository::select(dbQuery: $dbQuery);
    }

    public static function delete(int $ID): void
    {
        DB::get()->execute(
            sql: 'DELETE FROM news WHERE ID=?',
            parameters: [$ID]
        );
    }

    public static function insert(
        int $registeredByUserID,
        DateTimeImmutable $date,
        string $title,
        string $teaser,
        string $text,
        int $type,
        bool $archived
    ): void {
        DB::get()->execute(
            sql: '
                INSERT INTO news
                SET registered_by=?,
                    datum=?,
                    titel=?,
                    teaser=?,
                    text=?,
                    typ=?,
                    archiv=?
            ',
            parameters: [
                $registeredByUserID,
                $date->format(format: 'Y-m-d'),
                $title,
                $teaser,
                $text,
                $type,
                $archived ? 1 : 0
            ]
        );
    }

    public static function update(
        int $ID,
        DateTimeImmutable $date,
        string $title,
        string $teaser,
        string $text,
        bool $archived
    ): void {
        DB::get()->execute(
            sql: '
                UPDATE news
                SET datum=?,
                    titel=?,
                    teaser=?,
                    text=?,
                    archiv=?
                WHERE ID=?
            ',
            parameters: [
                $date->format(format: 'Y-m-d'),
                $title,
                $teaser,
                $text,
                $archived ? 1 : 0,
                $ID,
            ]
        );
    }
}