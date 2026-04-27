<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;

class DbMemberClubRepository
{
    public static function insert(
        int $memberID,
        int $clubID
    ): void {
        DB::get()->execute(
            sql: 'INSERT INTO benutzervereine SET benutzerID=?, vereinID=?',
            parameters: [$memberID, $clubID]
        );
    }

    public static function delete(
        int $memberID,
        int $clubID
    ): void {
        DB::get()->execute(
            sql: '
				DELETE FROM benutzervereine
				WHERE benutzerID=?
				  AND vereinID=?
			',
            parameters: [
                $memberID,
                $clubID,
            ]
        );
    }
}