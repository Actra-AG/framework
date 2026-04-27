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

class DbPageRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT page.ID,
                       page.datum AS date,
                       page.seite AS pageName,
                       page.inhalt AS htmlContent,
                       page.config AS phpContent,
                       CONCAT_WS(\' \', auth_user.firstName, auth_user.lastName) AS fullName
                FROM seiteninhalte page
                    INNER JOIN auth_user ON page.benutzerID=auth_user.ID
            '
        );
    }

    private static function createItem(stdClass $item): DbPage
    {
        return new DbPage(
            ID: $item->ID,
            htmlContent: $item->htmlContent,
            phpContent: $item->phpContent
        );
    }

    public static function select(DbQuery $dbQuery): DbPageCollection
    {
        $dbPageCollection = new DbPageCollection();
        foreach (
            $dbQuery->selectFromDb(
                db: DB::get(),
                offset: 0,
                rowCount: 1000
            ) as $item
        ) {
            $dbPageCollection->add(
                dbPage: DbPageRepository::createItem(item: $item)
            );
        }

        return $dbPageCollection;
    }

    public static function selectByID(int $ID): ?DbPage
    {
        $dbQuery = DbPageRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'page.ID=?',
            parameters: [$ID]
        );
        $dbPageCollection = DbPageRepository::select(dbQuery: $dbQuery);

        return $dbPageCollection->isEmpty() ? null : $dbPageCollection->first();
    }

    public static function insert(
        string $htmlFileName,
        string $html,
        string $php
    ): void {
        DB::get()->execute(
            sql: '
                INSERT INTO seiteninhalte
                SET benutzerID=?,
                    ort=?,
                    seite=?,
                    inhalt=?,
                    config=?
            ',
            parameters: [
                MyAuthUser::get()->ID,
                'frontend',
                $htmlFileName,
                $html,
                $php
            ]
        );
    }
}