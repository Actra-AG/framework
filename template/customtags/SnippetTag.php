<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\customtags;

use framework\Core;
use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagInline;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class SnippetTag extends TemplateTag implements TagNode, TagInline
{
    public static function getName(): string
    {
        return 'snippet';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return true;
    }

    public static function requireFile(string $file, TemplateEngine $tplEngine): void
    {
        if ($file === '') {
            echo '';

            return;
        }
        if (!str_ends_with(
            haystack: strtolower(string: $file),
            needle: '.html'
        )) {
            echo file_get_contents(filename: $file);
            return;
        }

        echo $tplEngine->getResultAsHtml(
            tplFile: $file,
            dataPool: $tplEngine->getAllData()
        );
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $newNode = new TextNode();
        $newNode->content = $this->getReplaceValue(snippetName: $elementNode->getAttribute(name: 'name')->value);
        $elementNode->parentNode->replaceNode(
            nodeToReplace: $elementNode,
            replacementNode: $newNode
        );
    }

    private function getReplaceValue(string $snippetName): string
    {
        $snippetPath = Core::get()->siteDirectory . 'snippets' . DIRECTORY_SEPARATOR . $snippetName;

        return '<?php ' . __CLASS__ . '::requireFile(file: \'' . $snippetPath . '\', tplEngine: $this); ?>';
    }

    public function replaceInline(TemplateEngine $tplEngine, array $tagArr): string
    {
        return $this->getReplaceValue(
            snippetName: $tagArr['name']
        );
    }
}