<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth;

use framework\auth\AuthSession;
use site\libs\auth\db\DbAuthLogin;
use site\libs\auth\db\DbAuthSession;
use site\libs\auth\db\DbAuthToken;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\db\DbAuthUserGroup;

class UserController
{
    public static function deleteUser(int $userID): void
    {
        DbAuthLogin::unsetUserID(userID: $userID);
        DbAuthSession::deleteByUserID(userID: $userID);
        DbAuthToken::deleteByUserID(userID: $userID);
        DbAuthUserGroup::deleteByUserID(userID: $userID);
        DbAuthUser::delete(ID: $userID);
        if (MyAuthUser::get()->ID === $userID) {
            AuthSession::logOut();
        }
    }
}