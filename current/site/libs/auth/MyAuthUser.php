<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth;

use framework\auth\AuthSession;
use framework\auth\AuthUser;
use framework\auth\Password;
use framework\core\HttpResponse;
use framework\exception\UnauthorizedException;
use site\libs\auth\db\DbAuthSession;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\db\DbAuthUserItem;
use site\libs\layout\BackendNavigation;
use site\view\BackendView;

class MyAuthUser extends AuthUser
{
    private static ?MyAuthUser $instance = null;

    private function __construct(
        public readonly DbAuthUserItem $dbAuthUserItem,
        public readonly ?int $parentSessionID
    ) {
        MyAuthUser::$instance = $this;
        parent::__construct(
            ID: $dbAuthUserItem->ID,
            isActive: (
                $dbAuthUserItem->isActive
                && !$dbAuthUserItem->accessRightCollection->isEmpty()
            ),
            wrongPasswordAttempts: 0,
            accessRightCollection: $dbAuthUserItem->accessRightCollection,
            password: Password::generateNew(rawPassword: 'unused')
        );
    }

    public static function createFromDbAuthUserItem(DbAuthUserItem $dbAuthUserItem): MyAuthUser
    {
        return new MyAuthUser(
            dbAuthUserItem: $dbAuthUserItem,
            parentSessionID: null
        );
    }

    public static function setRequestedPageAfterLogin(string $path): void
    {
        $_SESSION['requestedPageAfterLogin'] = $path;
    }

    public function redirectToFirstAllowedPage(): void
    {
        HttpResponse::redirectAndExit(relativeOrAbsoluteUri: $this->getFirstAllowedPage());
    }

    public function getFirstAllowedPage(): string
    {
        if (array_key_exists(
            key: 'requestedPageAfterLogin',
            array: $_SESSION
        )) {
            $target = $_SESSION['requestedPageAfterLogin'];
            unset($_SESSION['requestedPageAfterLogin']);
        } else {
            $target = BackendNavigation::get(myAuthUser: $this)->navigationItemCollection->getFirst()->href;
        }
        return $target . (str_contains(
                haystack: $target,
                needle: '?'
            ) ? '&' : '?') . BackendView::PARAM_FROM_LOGIN;
    }

    public static function get(): MyAuthUser
    {
        if (!is_null(value: MyAuthUser::$instance)) {
            return MyAuthUser::$instance;
        }
        $dbAuthSessionItem = DbAuthSession::selectByID(ID: AuthSession::getAuthSessionID());
        if (is_null(value: $dbAuthSessionItem)) {
            throw new UnauthorizedException();
        }

        return new MyAuthUser(
            dbAuthUserItem: $dbAuthSessionItem->dbAuthUserItem,
            parentSessionID: $dbAuthSessionItem->parentID
        );
    }

    public function getUserName(): string
    {
        return $this->dbAuthUserItem->firstName . ' ' . $this->dbAuthUserItem->lastName;
    }

    public function canImpersonateUser(DbAuthUserItem $dbAuthUserItem): bool
    {
        if ($this->isSessionChange()) {
            return false;
        }
        if ($dbAuthUserItem->ID === $this->ID) {
            return false;
        }
        if (!$dbAuthUserItem->isActive) {
            return false;
        }
        if ($dbAuthUserItem->accessRightCollection->isEmpty()) {
            return false;
        }

        return true;
    }

    public function isSessionChange(): bool
    {
        return !is_null(value: $this->parentSessionID);
    }

    protected function dbIncreaseWrongPasswordAttempts(): void
    {
    }

    protected function dbConfirmSuccessfulLogin(): int
    {
        DbAuthUser::dbConfirmSuccessfulLogin(ID: $this->ID);

        return DbAuthSession::insert(
            parentID: $this->parentSessionID,
            userID: $this->ID
        );
    }
}