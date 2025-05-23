<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\customtags;

use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class FormComponentTag extends TemplateTag implements TagNode
{
    public static function getName(): string
    {
        return 'formComponent';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return true;
    }

    public static function render($formSelector, $componentName, TemplateEngine $tplEngine): string
    {
        $callback = [$tplEngine->getDataFromSelector($formSelector), 'getChildComponent'];
        $component = call_user_func($callback, $componentName);

        return call_user_func([$component, 'render']);
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $tplEngine->checkRequiredAttributes($elementNode, ['form', 'name']);

        // DATA
        $newNode = new TextNode();
        $newNode->content = '<?= ' . FormComponentTag::class . '::render(\'' . $elementNode->getAttribute(
                'form'
            )->value . '\', \'' . $elementNode->getAttribute('name')->value . '\', $this); ?>';

        $elementNode->parentNode->insertBefore($newNode, $elementNode);
        $elementNode->parentNode->removeNode($elementNode);
    }
}