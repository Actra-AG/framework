<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\template;

interface TagNode
{
    public static function getName(): string;

    public static function isElseCompatible(): bool;

    public static function isSelfClosing(): bool;
}