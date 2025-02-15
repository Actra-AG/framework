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

class OptionsTag extends TemplateTag implements TagNode
{
    public static function getName(): string
    {
        return 'options';
    }

    public static function isElseCompatible(): bool
    {
        return false;
    }

    public static function isSelfClosing(): bool
    {
        return true;
    }

    public static function render(TemplateEngine $tplEngine, $optionsSelector, $selectedSelector): string
    {
        $options = $tplEngine->getDataFromSelector($optionsSelector);
        $selection = [];

        if ($selectedSelector !== null) {
            $selection = (array)$tplEngine->getDataFromSelector($selectedSelector);
        }

        return OptionsTag::renderOptions($options, $selection);
    }

    public static function renderOptions(array $options, array $selection): string
    {
        $html = '';

        foreach ($options as $key => $value) {
            if (is_array($value) === true) {
                $html .= '<optgroup label="' . $key . '">' . PHP_EOL . OptionsTag::renderOptions(
                        $value,
                        $selection
                    ) . '</optgroup>' . PHP_EOL;
            } else {
                $attributes = [
                    'option',
                    'value="' . $key . '"',
                ];
                if (in_array($key, $selection)) {
                    $attributes[] = 'selected';
                }
                $html .= '<' . implode(separator: ' ', array: $attributes) . '>' . $value . '</option>' . PHP_EOL;
            }
        }

        return $html;
    }

    public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
    {
        $tplEngine->checkRequiredAttributes($elementNode, ['options']);

        $selectionSelector = "'{$elementNode->getAttribute('selected')->value}'";
        $optionsSelector = "'{$elementNode->getAttribute('options')->value}'";

        $textContent = '<?php echo ' . __CLASS__ . '::render($this, ' . $optionsSelector . ', ' . $selectionSelector . '); ?>';

        $newNode = new TextNode();
        $newNode->content = $textContent;

        $elementNode->parentNode->insertBefore($newNode, $elementNode);
        $elementNode->parentNode->removeNode($elementNode);
    }
}