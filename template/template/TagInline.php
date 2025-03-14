<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\template;

interface TagInline
{
    public static function getName(): string;

    public function replaceInline(TemplateEngine $tplEngine, array $tagArr): string;
}