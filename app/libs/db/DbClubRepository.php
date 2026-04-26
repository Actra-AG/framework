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

class DbClubRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT club.ID,
                       club.name
                FROM vereine club
            '
        );
    }

    public static function select(DbQuery $dbQuery): DbClubCollection
    {
        $dbClubCollection = new DbClubCollection();
        foreach (
            $dbQuery->selectFromDb(
                db: DB::get(),
                offset: 0,
                rowCount: 1000
            ) as $item
        ) {
            $dbClubCollection->add(
                dbClub: DbClubRepository::createItem(item: $item)
            );
        }

        return $dbClubCollection;
    }

    public static function listAll(): DbClubCollection
    {
        $dbQuery = DbClubRepository::getDbQuery();
        $dbQuery->addOrderPart(
            column: 'club.name'
        );
        return DbClubRepository::select(dbQuery: $dbQuery);
    }

    private static function createItem(stdClass $item): DbClub
    {
        return new DbClub(
            ID: $item->ID,
            name: $item->name
        );
    }
}