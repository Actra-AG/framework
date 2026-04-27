<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;
use app\settings\EventCategoryEnum;

class DbEventCategoryRepository
{
    public static function insert(
        int $eventID,
        EventCategoryEnum $eventCategoryEnum
    ): void {
        DB::get()->execute(
            sql: '
                INSERT INTO eventCategory
                SET eventID=?,
                    categoryName=?
            ',
            parameters: [
                $eventID,
                $eventCategoryEnum->value,
            ]
        );
    }

    public static function delete(
        int $eventID,
        EventCategoryEnum $eventCategoryEnum
    ): void {
        DB::get()->execute(
            sql: '
                DELETE FROM eventCategory
                       WHERE eventID=?
                  AND categoryName=?
            ',
            parameters: [
                $eventID,
                $eventCategoryEnum->value,
            ]
        );
    }
}