<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use site\libs\db\DB;
use stdClass;

class DbAuthGroup
{
    public const string SELECT_QUERY = '
		SELECT auth_group.ID,
		       auth_group.title
		FROM auth_group
	';
    private static ?DbAuthGroupCollection $cache = null;

    public static function selectByID(int $ID): ?DbAuthGroupItem
    {
        return array_find(
            array: DbAuthGroup::listAll()->items,
            callback: fn($dbAuthGroupItem) => $ID === $dbAuthGroupItem->ID
        );
    }

    public static function listAll(): DbAuthGroupCollection
    {
        if (is_null(value: DbAuthGroup::$cache)) {
            DbAuthGroup::$cache = DbAuthGroup::listByCond(
                whereCond: '',
                parameters: []
            );
        }

        return DbAuthGroup::$cache;
    }

    private static function listByCond(string $whereCond, array $parameters): DbAuthGroupCollection
    {
        $dbAuthGroupCollection = new DbAuthGroupCollection();
        foreach (
            DB::getHAAS()->select(
                sql: DbAuthGroup::SELECT_QUERY . $whereCond . ' ORDER BY auth_group.title',
                parameters: $parameters
            ) as $item
        ) {
            $dbAuthGroupCollection->add(dbAuthGroupItem: DbAuthGroup::createDbAuthGroupItem(data: $item));
        }

        return $dbAuthGroupCollection;
    }

    private static function createDbAuthGroupItem(stdClass $data): DbAuthGroupItem
    {
        return new DbAuthGroupItem(
            ID: $data->ID,
            title: $data->title
        );
    }

    public static function listByUserID(int $userID): ?DbAuthGroupCollection
    {
        return DbAuthGroup::listByCond(
            whereCond: 'WHERE auth_group.ID IN (SELECT groupID FROM auth_user_group WHERE userID=?)',
            parameters: [
                $userID,
            ]
        );
    }
}