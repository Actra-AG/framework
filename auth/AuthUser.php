<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\auth;

use LogicException;

abstract class AuthUser
{
    private static ?AuthUser $instance = null;

    public function __construct(
        public readonly int $ID,
        public readonly bool $isActive,
        private(set) int $wrongPasswordAttempts,
        private readonly AccessRightCollection $accessRightCollection,
        private(set) Password $password
    ) {
        if (!is_null(value: AuthUser::$instance)) {
            throw new LogicException(message: 'There can only be one AuthUser instance.');
        }
        AuthUser::$instance = $this;
    }

    protected static function resetInstance(): void
    {
        AuthUser::$instance = null;
    }

    public function hasOneOfRights(AccessRightCollection $accessRightCollection): bool
    {
        if (!$this->isActive) {
            return false;
        }

        return $this->accessRightCollection->hasOneOfAccessRights(accessRightCollection: $accessRightCollection);
    }

    public function increaseWrongPasswordAttempts(): void
    {
        $this->dbIncreaseWrongPasswordAttempts();
        $this->wrongPasswordAttempts++;
    }

    abstract protected function dbIncreaseWrongPasswordAttempts(): void;

    public function confirmSuccessfulLogin(): int
    {
        $this->wrongPasswordAttempts = 0;

        return $this->dbConfirmSuccessfulLogin();
    }

    abstract protected function dbConfirmSuccessfulLogin(): int;

    protected function changePassword(Password $newPassword): void
    {
        $this->password = $newPassword;
    }
}