<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\settings;

enum AuthRightEnum: string
{
    case BACKEND_ACCESS = 'backend_access';
    case MANAGE_USERS = 'manage_users';
    case BOARD_MEMBER = 'board_member';
    case EDITOR = 'editor';
}