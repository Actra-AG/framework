<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\common;

readonly class CalendarGroup
{
    private function __construct(public array $items)
    {
    }

    public static function get(string $group, bool $public): ?CalendarGroup
    {
        $jpArr['sa'] = ['sa300', 'sa50', 'sa25', 'sa10'];
        $jpArr['js'] = [];
        $jpArr['mw'] = ['mw300', 'mw50', 'mwlg', 'mwlp'];
        $jpArr['gm'] = ['gm300', 'gm50', 'gm25', 'gm10'];
        $jpArr['vs'] = [];
        $jpArr['vt'] = [];
        $jpArr['wb'] = [];
        if (!$public) {
            $jpArr['vorstand'] = [];
        }
        return array_key_exists(
          key: $group,
          array: $jpArr
        ) ? new CalendarGroup(
          items: $jpArr[$group]
        ) : null;
    }
}