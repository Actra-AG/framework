<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\common;

class StringUtils
{
    public const string IMPLODE_DEFAULT_SEPARATOR = ''; // https://github.com/php/php-src/issues/10197

    public static function afterFirst(string $str, string $after): string
    {
        $posFrom = mb_strpos($str, $after);
        if ($posFrom === false) {
            return '';
        }

        return mb_substr($str, $posFrom + mb_strlen($after));
    }

    public static function beforeFirst(string $str, string $before): string
    {
        $posUntil = mb_strpos($str, $before);
        if ($posUntil === false) {
            return $str;
        }

        return mb_substr($str, 0, $posUntil);
    }

    public static function between(string $str, string $start, string $end): ?string
    {
        $posStart = mb_strpos($str, $start) + mb_strlen($start);
        $posEnd = mb_strrpos($str, $end, $posStart);
        if ($posEnd === false) {
            return null;
        }

        return mb_substr($str, $posStart, $posEnd - $posStart);
    }

    public static function insertBeforeLast(string $str, string $beforeLast, string $newStr): string
    {
        return StringUtils::beforeLast($str, $beforeLast) . $newStr . $beforeLast . StringUtils::afterLast(
                $str,
                $beforeLast
            );
    }

    public static function beforeLast(string $str, string $before): string
    {
        $posUntil = mb_strrpos($str, $before);
        if ($posUntil === false) {
            return $str;
        }

        return mb_substr($str, 0, $posUntil);
    }

    public static function afterLast(string $str, string $after): ?string
    {
        $posFrom = mb_strrpos($str, $after);

        if ($posFrom === false) {
            return null;
        }

        return mb_substr($str, $posFrom + mb_strlen($after));
    }

    public static function breakUp(string $sentence, int $atIndex): string
    {
        if (mb_strlen($sentence) > $atIndex) {
            return StringUtils::beforeLast(mb_substr($sentence, 0, 50), " ");
        }

        return $sentence;
    }

    public static function tokenize(string $stringToSplit, string $tokenToSplitString): array
    {
        $tokenArr = [];
        $tokStr = strtok($stringToSplit, $tokenToSplitString);

        while ($tokStr !== false) {
            $tokenArr[] = $tokStr;

            $tokStr = strtok($tokenToSplitString);
        }

        return $tokenArr;
    }

    public static function explode(string|array $tokens, string $str): array
    {
        $strToExplode = $str;
        $explodeStr = $tokens;

        if (is_array($tokens) === true) {
            $explodeStr = chr(31);
            $strToExplode = str_replace($tokens, $explodeStr, $str);
        }

        return explode($explodeStr, $strToExplode);
    }

    public static function urlify(string $string, string $separator = '-', int $maxLength = 0): string
    {
        $string = iconv(
            from_encoding: 'UTF-8',
            to_encoding: 'ASCII//TRANSLIT',
            string: $string
        );
        $string = preg_replace(
            pattern: '/[^a-zA-Z0-9\-.]/',
            replacement: '-',
            subject: $string
        );
        $string = preg_replace(
            pattern: '/-+/',
            replacement: '-',
            subject: $string
        );
        $string = trim(
            string: $string,
            characters: '-'
        );
        if ($separator !== '-') {
            str_replace(
                search: '-',
                replace: $separator,
                subject: $string
            );
        }
        if ($string === '') {
            $string = 'unbenannt';
        }
        $string = strtolower(string: $string);

        return $maxLength === 0 ? $string : substr(
            string: $string,
            offset: 0,
            length: $maxLength
        );
    }

    public static function emptyToNull(string $string): ?string
    {
        return ($string === '') ? null : $string;
    }

    public static function utf8_to_punycode_email(string $email): string
    {
        $fragments = explode(separator: '@', string: $email);
        $lastFragment = array_pop(array: $fragments);

        return implode(separator: '@', array: $fragments) . '@' . idn_to_ascii(domain: $lastFragment);
    }

    public static function punycode_to_utf8_email(string $email): string
    {
        $fragments = explode(separator: '@', string: $email);
        $lastFragment = array_pop(array: $fragments);

        return implode(separator: '@', array: $fragments) . '@' . idn_to_utf8(domain: $lastFragment);
    }

    public static function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(num: ($bytes ? log(num: $bytes) : 0) / log(num: 1024));
        $pow = min($pow, count(value: $units) - 1);
        $bytes /= pow(num: 1024, exponent: $pow);

        return round(num: $bytes, precision: $precision) . ' ' . $units[$pow];
    }

    /**
     * Generates a random character string of given length, where characters, which could be easily mixed up, were avoided.
     * Set $cryptoSecurity to true to use a cryptographically secure pseudorandom number generator (random_int):
     * - https://stackoverflow.com/a/31107425/31107425
     * - http://stackoverflow.com/a/31284266/2224584
     *
     * @param int $requiredStringLength Required length of the random string
     * @param bool $noSpecialChars Set to true to only use numbers and letters
     *
     * @return string
     */
    public static function randomString(int $requiredStringLength, bool $noSpecialChars): string
    {
        $requiredStringLength = ($requiredStringLength < 1) ? 1 : $requiredStringLength;

        $characterSets = [
            'abcdefghjkmnpqrstuvwxyz',
            'ABCDEFGHJKMNPQRSTUVWXYZ',
            '23456789',
        ];
        if (!$noSpecialChars) {
            $characterSets[] = '!@#$%&*?';
        }

        $unShuffledRandomString = '';
        foreach ($characterSets as $characterSet) {
            $unShuffledRandomString .= $characterSet[mt_rand(
                min: 0,
                max: mb_strlen(string: $characterSet, encoding: '8bit') - 1
            )];
        }

        $allCharacters = implode(separator: StringUtils::IMPLODE_DEFAULT_SEPARATOR, array: $characterSets);
        $currentRandomStringLength = mb_strlen(string: $unShuffledRandomString);
        while ($currentRandomStringLength < $requiredStringLength) {
            $unShuffledRandomString .= $allCharacters[mt_rand(
                min: 0,
                max: mb_strlen(string: $allCharacters, encoding: '8bit') - 1
            )];
            $currentRandomStringLength++;
        }

        return str_shuffle(string: $unShuffledRandomString);
    }

    public static function generateSalt(int $length = 16): string
    {
        $chars = '`´°+*ç%&/()=?abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890üöä!£{}éèà[]¢|¬§°#@¦';
        $charsLength = mb_strlen(string: $chars);
        $salt = '';
        for ($i = 0; $i < $length; $i++) {
            $salt .= mb_substr(string: $chars, start: (rand() % $charsLength), length: 1);
        }

        return $salt;
    }
}