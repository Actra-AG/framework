<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use site\libs\db\DB;

class DbAuthUserGroup
{
    public static function insert(
        int $userID,
        int $groupID
    ): void {
        DB::getHAAS()->execute(
            sql: '
				INSERT INTO auth_user_group
				SET userID=?,
				    groupID=?
			',
            parameters: [
                $userID,
                $groupID,
            ]
        );
    }

    public static function delete(
        int $userID,
        int $groupID
    ): void {
        DB::getHAAS()->execute(
            sql: '
				DELETE FROM auth_user_group
				WHERE userID=?
				  AND groupID=?
			',
            parameters: [
                $userID,
                $groupID,
            ]
        );
    }

    public static function deleteByUserID(int $userID): void
    {
        DB::getHAAS()->execute(
            sql: '
                DELETE FROM auth_user_group
                WHERE ID>0
                  AND userID=?
            ',
            parameters: [
                $userID,
            ]
        );
    }
}