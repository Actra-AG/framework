<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth;

use framework\auth\Authenticator;
use framework\auth\AuthMethod;
use framework\auth\AuthResult;
use framework\auth\AuthUser;
use site\libs\auth\db\DbAuthLogin;
use site\libs\auth\db\DbAuthToken;
use site\libs\auth\db\DbAuthUser;
use site\libs\auth\db\DbAuthUserItem;
use site\libs\auth\email\EmailLoginToken;
use site\settings\AuthTokenTypeEnum;

class MyAuthenticator extends Authenticator
{
    private static ?MyAuthenticator $instance = null;
    private(set) MyAuthUser $user;

    private function __construct()
    {
        MyAuthenticator::$instance = $this;
        parent::__construct(maxAllowedWrongPasswordAttempts: 5);
    }

    public static function get(): MyAuthenticator
    {
        return is_null(value: MyAuthenticator::$instance) ? new MyAuthenticator() : MyAuthenticator::$instance;
    }

    public function createAndSendAuthToken(DbAuthUserItem $dbAuthUserItem): void
    {
        $_SESSION['auth_token'] = DbAuthToken::createToken(
            dbAuthUserItem: $dbAuthUserItem,
            authTokenType: AuthTokenTypeEnum::LOGIN
        );
        $_SESSION['failedLoginAttempts'] = 0;
        EmailLoginToken::send(
            dbAuthUserItem: $dbAuthUserItem,
            loginCode: $_SESSION['auth_token']
        );
    }

    public function tokenLogin(string $inputToken): bool
    {
        if ($this->getFailedLoginAttempts() > 5) {
            return false;
        }
        if (
            !array_key_exists(
                key: 'auth_token',
                array: $_SESSION
            )
            || $_SESSION['auth_token'] !== $inputToken
        ) {
            $this->increaseFailedLoginAttempts();
            return false;
        }
        unset($_SESSION['auth_token']);
        $dbAuthTokenItem = DbAuthToken::getClaimable(
            authTokenType: AuthTokenTypeEnum::LOGIN,
            token: $inputToken
        );
        if (is_null(value: $dbAuthTokenItem)) {
            return false;
        }
        DbAuthToken::claim(dbAuthTokenItem: $dbAuthTokenItem);

        return $this->doLogin(
            authMethod: AuthMethod::OTP,
            userName: $dbAuthTokenItem->email,
            passwordToCheck: null
        );
    }

    private function getFailedLoginAttempts(): int
    {
        return array_key_exists(key: 'failedLoginAttempts', array: $_SESSION) ? $_SESSION['failedLoginAttempts'] : 0;
    }

    private function increaseFailedLoginAttempts(): void
    {
        if (!array_key_exists(key: 'failedLoginAttempts', array: $_SESSION)) {
            $_SESSION['failedLoginAttempts'] = 0;
        }
        $_SESSION['failedLoginAttempts']++;
    }

    protected function checkLoginCredentials(AuthUser $authUser): bool
    {
        return true;
    }

    protected function createAuthUserByUserName(string $userName): ?MyAuthUser
    {
        $dbAuthUserItem = DbAuthUser::selectByEmail(email: $userName);
        if (is_null(value: $dbAuthUserItem)) {
            return null;
        }
        $this->user = MyAuthUser::createFromDbAuthUserItem(dbAuthUserItem: $dbAuthUserItem);
        return $this->user;
    }

    protected function logAuthResult(
        ?int $userID,
        string $sessionID,
        string $ip,
        string $userName,
        AuthResult $authResult
    ): void {
        DbAuthLogin::insert(
            userID: $userID,
            sessionID: $sessionID,
            ipAddress: $ip,
            inputEmail: $userName,
            authResult: $authResult
        );
    }
}