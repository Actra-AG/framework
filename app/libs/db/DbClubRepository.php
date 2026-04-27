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

    public static function listMemberClubs(int $memberID): DbClubCollection
    {
        $dbQuery = DbClubRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'club.ID IN (SELECT vereinID FROM benutzervereine WHERE benutzerID=?)',
            parameters: [$memberID]
        );
        return DbClubRepository::select(dbQuery: $dbQuery);
    }

    public static function delete(int $ID): void
    {
        DB::get()->execute(
            sql: 'DELETE FROM vereine WHERE ID=?',
            parameters: [$ID]
        );
    }

    public static function insert(string $name): void
    {
        DB::get()->execute(
            sql: 'INSERT INTO vereine SET registered_by=?, name=?',
            parameters: [
                MyAuthUser::get()->ID,
                $name,
            ]
        );
    }

    public static function selectByID(int $ID): ?DbClub
    {
        $dbQuery = DbClubRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'club.ID=?',
            parameters: [$ID]
        );
        $dbClubCollection = DbClubRepository::select(dbQuery: $dbQuery);

        return $dbClubCollection->isEmpty() ? null : $dbClubCollection->first();
    }

    public static function update(
        int $ID,
        string $name
    ): void {
        DB::get()->execute(
            sql: '
                UPDATE vereine
                SET name=?
                WHERE ID=?
            ',
            parameters: [
                $name,
                $ID,
            ]
        );
    }
}