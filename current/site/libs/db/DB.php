<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\db;

use actra\yuf\db\DbSettingsModel;
use actra\yuf\db\FrameworkDB;
use site\settings\EnvSettings;

class DB extends FrameworkDB
{
    private static ?DB $instance = null;

    public static function get(): DB
    {
        if (!is_null(DB::$instance)) {
            return DB::$instance;
        }

        return DB::$instance = new DB(
            new DbSettingsModel(
                identifier: EnvSettings::DB_IDENTIFIER,
                hostName: EnvSettings::DB_HOSTNAME,
                databaseName: EnvSettings::DB_DATABASE,
                userName: EnvSettings::DB_USERNAME,
                password: EnvSettings::DB_PASSWORD,
                charset: null,
                timeNamesLanguage: 'de_CH',
                sqlSafeUpdates: true
            )
        );
    }
}