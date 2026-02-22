<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use DateTimeImmutable;
use framework\auth\AccessRightCollection;

readonly class DbAuthUserItem
{
    public function __construct(
        public int $ID,
        public DateTimeImmutable $registered,
        public ?DateTimeImmutable $invitedDate,
        private ?DateTimeImmutable $lastLogin,
        public string $email,
        public bool $isActive,
        public AccessRightCollection $accessRightCollection,
        public string $firstName,
        public string $lastName
    ) {
        $this->accessRightCollection->add(accessRight: AccessRightCollection::ACCESS_DO_PASSWORD_LOGIN);
    }

    public function isInvited(): bool
    {
        return !is_null(value: $this->invitedDate);
    }

    public function renderLastLogin(): string
    {
        return is_null(value: $this->lastLogin) ? '' : $this->lastLogin->format(format: 'd.m.Y H:i:s');
    }

    public function renderActive(): string
    {
        return $this->isActive ? 'aktiv' : 'inaktiv';
    }
}