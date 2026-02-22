<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use DateTimeImmutable;
use framework\auth\AccessRightCollection;
use framework\core\HttpRequest;
use site\libs\db\DB;
use stdClass;

class DbAuthSession
{
    private const string SELECT_QUERY = '
		SELECT auth_session.ID,
		       auth_session.parentID,
		       auth_user.ID AS userID,
		       auth_user.registered,
		       auth_user.invited,
		       (SELECT MAX(registered) FROM auth_login WHERE userID=auth_user.ID) AS lastLogin,
		       auth_user.email,
		       auth_user.active,
		       auth_user.firstName,
		       auth_user.lastName,
		       (SELECT GROUP_CONCAT(auth_group_right.rightName) FROM auth_group_right WHERE auth_group_right.groupID IN (SELECT groupID FROM auth_user_group WHERE userID=auth_user.ID)) AS accessRights
		FROM auth_session
		    INNER JOIN auth_user ON auth_user.ID=auth_session.userID
	';

    public static function insert(
        ?int $parentID,
        int $userID
    ): int {
        $db = DB::getHAAS();
        $db->execute(
            sql: '
                INSERT INTO auth_session
                SET parentID=?,
                    userID=?,
                    sessionId=?,
                    ipAddress=?
            ',
            parameters: [
                $parentID,
                $userID,
                session_id(),
                HttpRequest::getRemoteAddress(),
            ]
        );

        return $db->lastInsertId();
    }

    public static function selectByID(int $ID): ?DbAuthSessionItem
    {
        $res = DB::getHAAS()->select(
            sql: DbAuthSession::SELECT_QUERY . ' WHERE auth_session.ID=?',
            parameters: [
                $ID,
            ]
        );

        return (count(value: $res) === 0) ? null : DbAuthSession::createMyDbAuthSessionItem(data: $res[0]);
    }

    private static function createMyDbAuthSessionItem(stdClass $data): DbAuthSessionItem
    {
        return new DbAuthSessionItem(
            ID: $data->ID,
            parentID: $data->parentID,
            dbAuthUserItem: new DbAuthUserItem(
                ID: $data->userID,
                registered: new DateTimeImmutable(datetime: $data->registered),
                invitedDate: is_null(value: $data->invited) ? null : new DateTimeImmutable(datetime: $data->invited),
                lastLogin: is_null(value: $data->lastLogin) ? null : new DateTimeImmutable(datetime: $data->lastLogin),
                email: $data->email,
                isActive: ($data->active === 1),
                accessRightCollection: AccessRightCollection::createFromStringArray(
                    input: explode(
                        separator: ',',
                        string: (string)$data->accessRights
                    )
                ),
                firstName: $data->firstName,
                lastName: $data->lastName
            )
        );
    }

    public static function updateLastAction(int $ID): void
    {
        DB::getHAAS()->execute(
            sql: '
                UPDATE auth_session
                SET lastAction=NOW()
                WHERE ID=?
            ',
            parameters: [$ID]
        );
    }

    public static function deleteByUserID(int $userID): void
    {
        $db = DB::getHAAS();
        $db->execute(
            sql: '
                DELETE FROM auth_session
                       WHERE ID>0
                         AND parentID IN (SELECT ID FROM auth_session WHERE userID=?)
            ',
            parameters: [
                $userID,
            ]
        );
        $db->execute(
            sql: '
                DELETE FROM auth_session
                       WHERE userID=?
            ',
            parameters: [
                $userID,
            ]
        );
    }
}