<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\db;

use LogicException;

class DbSettingsModel
{
    public const string IDX_IDENTIFIER = 'identifier';
    public const string IDX_HOSTNAME = 'hostname';
    public const string IDX_DATABASE = 'database';
    public const string IDX_USERNAME = 'username';
    public const string IDX_PASSWORD = 'password';
    private static array $instances = [];
    public readonly string $charset;

    public function __construct(
        public readonly string $identifier,
        public readonly string $hostName,
        public readonly string $databaseName,
        public readonly string $userName,
        public readonly string $password,
        ?string $charset,
        public readonly ?string $timeNamesLanguage,
        public readonly bool $sqlSafeUpdates
    ) {
        if (array_key_exists(key: $identifier, array: DbSettingsModel::$instances)) {
            throw new LogicException(message: 'There is already an instance with the identifier ' . $identifier);
        }
        DbSettingsModel::$instances[$identifier] = $this;

        $charset = trim(string: (string)$charset);
        if ($charset === '') {
            $charset = 'utf8mb4';
        }
        if (mb_strtolower(string: $charset) === 'utf-8') {
            throw new LogicException(message: 'Faulty charset setting string "utf-8". Must be "utf8" for PDO driver.');
        }
        $this->charset = $charset;
    }
}