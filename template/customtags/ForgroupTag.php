<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\customtags;

use framework\datacheck\Sanitizer;
use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class ForgroupTag extends TemplateTag implements TagNode
{
    private ?string $var = null;
    private ?string $no = null;

    public static function getName(): string
    {
        return 'forgroup';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return false;
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $var = Sanitizer::trimmedString($elementNode->getAttribute('var')->value);
        $entryNoArr = explode(':', $var);
        $this->no = $entryNoArr[0];
        $this->var = $entryNoArr[1];

        $tplEngine->checkRequiredAttributes($elementNode, ['var']);

        $replNode = new TextNode();

        $varName = $this->var . $this->no;

        $replNode->content = '<?php $tmpGrpVal = $this->getDataFromSelector(\'' . $varName . '\', true);' . PHP_EOL;
        $replNode->content .= ' if($tmpGrpVal !== null) {' . PHP_EOL;
        $replNode->content .= '$this->addData(\'' . $this->var . '\', $tmpGrpVal, true); ?>';
        $replNode->content .= ForgroupTag::prepareHtml($elementNode->getInnerHtml());
        $replNode->content .= "<?php } ?>";

        $elementNode->parentNode->replaceNode($elementNode, $replNode);
    }

    private function prepareHtml($html): array|string|null
    {
        $newHtml = preg_replace_callback(
            pattern: '/{' . $this->var . '\.(.*?)}/',
            callback: [$this, 'replace'],
            subject: $html
        );

        return preg_replace_callback('/{(\w+?)(?:\.([\w|.]+))?}/', [$this, 'replaceForeign'], $newHtml);
    }

    private function replaceForeign($matches): string
    {
        return '<?php echo $' . $matches[1] . '->' . str_replace('.', '->', $matches[2]) . '; ?>';
    }

    private function replace($matches): string
    {
        return '<?php echo $' . $this->var . $this->no . '->' . str_replace('.', '->', $matches[1]) . '; ?>';
    }
}