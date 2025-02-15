<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\customtags;

use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class IfTag extends TemplateTag implements TagNode
{
    public static function getName(): string
    {
        return 'if';
    }

    public static function isElseCompatible(): bool
    {
        return true;
    }

    public static function isSelfClosing(): bool
    {
        return false;
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $tplEngine->checkRequiredAttributes(
            contextTag: $elementNode,
            attributes: [
                'compare',
                'operator',
                'against',
            ]
        );
        $compareAttr = $elementNode->getAttribute(name: 'compare')->value;
        $operatorAttr = $elementNode->getAttribute(name: 'operator')->value;
        $againstAttr = $elementNode->getAttribute(name: 'against')->value;

        $phpCode = '<?php ';
        if (strlen(string: $againstAttr) === 0) {
            $againstAttr = "''";
        } elseif (!in_array(needle: strtolower(string: $againstAttr), haystack: ['null', 'true', 'false'])) {
            $againstAttr = "'" . $againstAttr . "'";
        }
        $phpCode .= 'if($this->getDataFromSelector(\'' . $compareAttr . '\') ' . match (strtolower(
                string: $operatorAttr
            )) {
                'gt' => '>',
                'ge' => '>=',
                'lt' => '<',
                'le' => '<=',
                'ne' => '!=',
                default => '=='
            } . ' ' . $againstAttr . ') { ?>';
        $phpCode .= $elementNode->getInnerHtml();
        if (!$tplEngine->isFollowedBy(elementNode: $elementNode, tagNames: ['else', 'elseif'])) {
            $phpCode .= '<?php } ?>';
        }
        $textNode = new TextNode();
        $textNode->content = $phpCode;
        $elementNode->parentNode->replaceNode(nodeToReplace: $elementNode, replacementNode: $textNode);
        $elementNode->parentNode->removeNode(nodeToRemove: $elementNode);
    }
}