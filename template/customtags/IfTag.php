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
use LogicException;

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
        if (strlen(string: $againstAttr) === 0) {
            $againstAttr = "''";
        } elseif (!in_array(needle: strtolower(string: $againstAttr), haystack: ['null', 'true', 'false'])) {
            $againstAttr = "'" . $againstAttr . "'";
        }
        $phpCode = '<?php ';
        $phpCode .= '$compareValue = $this->getDataFromSelector(\'' . $compareAttr . '\');';
        $phpCode .= 'if(' . match (strtolower(
                string: $operatorAttr
            )) {
                'gt' => '$compareValue > ' . $againstAttr,
                'ge' => '$compareValue >= ' . $againstAttr,
                'lt' => '$compareValue < ' . $againstAttr,
                'le' => '$compareValue <= ' . $againstAttr,
                'ne' => '$compareValue != ' . $againstAttr,
                'eq' => '$compareValue == ' . $againstAttr,
                'in' => 'in_array($compareValue, explode(\' \', '.$againstAttr.'))',
                default => throw new LogicException(message: 'Unknown operator "'.$operatorAttr.'"')
            } . ') { ?>';
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