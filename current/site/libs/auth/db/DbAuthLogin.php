<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use framework\auth\AuthResult;
use site\libs\db\DB;

class DbAuthLogin
{
    public static function insert(
        ?int $userID,
        string $sessionID,
        string $ipAddress,
        string $inputEmail,
        AuthResult $authResult
    ): void {
        DB::getHAAS()->execute(
            sql: '
                INSERT INTO auth_login
                SET userID=?, 
                    sessionId=?, 
                    ipAddress=?, 
                    email=?, 
                    result=?
            ',
            parameters: [
                $userID,
                $sessionID,
                $ipAddress,
                $inputEmail,
                $authResult->value,
            ]
        );
    }

    public static function unsetUserID(int $userID): void
    {
        DB::getHAAS()->execute(
            sql: '
                UPDATE auth_login SET userID=NULL WHERE userID=?
            ',
            parameters: [
                $userID,
            ]
        );
    }
}