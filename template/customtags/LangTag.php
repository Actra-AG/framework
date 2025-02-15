<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\customtags;

use framework\core\LocaleHandler;
use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagInline;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class LangTag extends TemplateTag implements TagNode, TagInline
{
    public static function getName(): string
    {
        return 'lang';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return true;
    }

    public static function getText($key, array $phpVars): string
    {
        return LocaleHandler::get()->getText(key: $key, replacements: $phpVars);
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $replValue = LangTag::replace(
            $elementNode->getAttribute('key')->value,
            $elementNode->getAttribute('vars')->value
        );

        $replNode = new TextNode();
        $replNode->content = $replValue;

        $elementNode->parentNode->replaceNode($elementNode, $replNode);
    }

    public function replace($key, ?string $vars = null): string
    {
        $phpVars = ', array()';
        if (!is_null($vars)) {
            $varsEx = explode(',', $vars);
            $varsFull = [];

            foreach ($varsEx as $v) {
                $varsFull[] = '\'' . $v . '\' => LangTag::getData(\'' . $v . '\')';
            }

            $phpVars = ',array(' . implode(separator: ', ', array: $varsFull) . ')';
        }

        return '<?php echo ' . __CLASS__ . '::getText(\'' . $key . '\'' . $phpVars . '); ?>';
    }

    public function replaceInline(TemplateEngine $tplEngine, $tagArr): string
    {
        $vars = (array_key_exists('vars', $tagArr)) ? $tagArr['vars'] : null;

        return LangTag::replace($tagArr['key'], $vars);
    }
}