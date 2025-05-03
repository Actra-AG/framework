<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\customtags;

use Exception;
use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class LoadSubTplTag extends TemplateTag implements TagNode
{
    public static function getName(): string
    {
        return 'loadSubTpl';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return true;
    }

    /**
     * A special method that belongs to the LoadSubTplTag class but needs none static properties from this class and is called from the cached template files.
     *
     * @param string $file The full filepath to include (OR magic {this})
     * @param TemplateEngine $tplEngine
     */
    public static function requireFile(string $file, TemplateEngine $tplEngine): void
    {
        if ($file === '') {
            echo '';

            return;
        }
        echo $tplEngine->getResultAsHtml(tplFile: $file, dataPool: $tplEngine->getAllData());
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $dataKey = $elementNode->getAttribute(name: 'tplfile')->getValue();
        $tplFile = (preg_match(
                pattern: '/^{(.+)}$/',
                subject: $dataKey,
                matches: $res
            ) === 1) ? '$this->getData(\'' . $res[1] . '\')' : '\'' . $dataKey . '\'';

        $newNode = new TextNode();
        $newNode->content = '<?php ' . __NAMESPACE__ . '\\LoadSubTplTag::requireFile(' . $tplFile . ', $this); ?>';

        $elementNode->parentNode->replaceNode(nodeToReplace: $elementNode, replacementNode: $newNode);
    }

    public function replaceInline()
    {
        throw new Exception(message: 'Don\'t use this tag (LoadSubTpl) inline!');
    }
}