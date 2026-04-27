<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;

class DbFileFormatRepository
{
    public static function getExtensionByMimeType(string $mimeType): ?string
    {
        $res = DB::get()->select(
            sql: '
                SELECT extension
                FROM dateiformate
                WHERE mimetype=? AND FIND_IN_SET(?,arten)
            ',
            parameters: [
                $mimeType,
                'dokumente',
            ]
        );
        return $res === [] ? null : $res[0]->extension;
    }
}