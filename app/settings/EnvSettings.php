<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\settings;

use actra\yuf\Core;

class EnvSettings
{
    public static function getMailerHostname(): string
    {
        return Core::config(key: 'mailer.hostname');
    }

    public static function getMailerPort(): int
    {
        return (int)Core::config(key: 'mailer.port');
    }

    public static function getMailerUsername(): string
    {
        return Core::config(key: 'mailer.username');
    }

    public static function getMailerPassword(): string
    {
        return Core::config(key: 'mailer.password');
    }

    public static function getMailerTls(): bool
    {
        return (bool)Core::config(key: 'mailer.tls');
    }

    public static function getDbIdentifier(): string
    {
        return Core::config(key: 'db.identifier');
    }

    public static function getDbHostname(): string
    {
        return Core::config(key: 'db.hostname');
    }

    public static function getDbUsername(): string
    {
        return Core::config(key: 'db.username');
    }

    public static function getDbPassword(): string
    {
        return Core::config(key: 'db.password');
    }

    public static function getDbDatabase(): string
    {
        return Core::config(key: 'db.database');
    }
}