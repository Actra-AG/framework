<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\auth;

class AccessRightCollection
{
    public const string ACCESS_DO_PASSWORD_LOGIN = 'doPasswordLogin';

    /** @var string[] */
    private array $accessRights = [];

    protected function __construct()
    {
    }

    public static function createEmpty(): AccessRightCollection
    {
        return new AccessRightCollection();
    }

    public static function createFromStringArray(array $input): AccessRightCollection
    {
        $accessRightCollection = new AccessRightCollection();
        foreach ($input as $value) {
            $accessRightCollection->add(accessRight: $value);
        }

        return $accessRightCollection;
    }

    public function add(string $accessRight): void
    {
        $this->accessRights[] = $accessRight;
    }

    public function hasOneOfAccessRights(AccessRightCollection $accessRightCollection): bool
    {
        foreach ($accessRightCollection->listAccessRights() as $accessRight) {
            if ($this->hasAccessRight(accessRight: $accessRight)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function listAccessRights(): array
    {
        return $this->accessRights;
    }

    public function hasAccessRight(string $accessRight): bool
    {
        return in_array(needle: $accessRight, haystack: $this->accessRights);
    }

    public function isEmpty(): bool
    {
        return (count(value: $this->accessRights) === 0);
    }
}