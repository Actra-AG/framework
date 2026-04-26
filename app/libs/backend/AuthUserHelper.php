<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\backend;

use actra\backend\libs\auth\MyAuthUser;
use app\settings\AuthRightEnum;

class AuthUserHelper
{
    public static function isEditor(): bool
    {
        return (MyAuthUser::get()->dbAuthUser->accessRightCollection->hasAccessRight(
            accessRight: AuthRightEnum::EDITOR->value,
        ));
    }

    public static function isBoard(): bool
    {
        return (MyAuthUser::get()->dbAuthUser->accessRightCollection->hasAccessRight(
            accessRight: AuthRightEnum::BOARD_MEMBER->value,
        ));
    }

    public static function isAdmin(): bool
    {
        return (MyAuthUser::get()->canManageUsers());
    }
}