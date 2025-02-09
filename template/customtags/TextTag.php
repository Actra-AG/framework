<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\customtags;

use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagInline;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class TextTag extends TemplateTag implements TagNode, TagInline
{
	public static function getName(): string
	{
		return 'text';
	}

	public static function isElseCompatible(): bool
	{
		return false;
	}

	public static function isSelfClosing(): bool
	{
		return true;
	}

	public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void
	{
		$replValue = $this->replace($elementNode->getAttribute('value')->getValue());

		$replNode = new TextNode();
		$replNode->content = $replValue;

		$elementNode->parentNode->replaceNode($elementNode, $replNode);
	}

	public function replaceInline(TemplateEngine $tplEngine, $tagArr): string
	{
		return $this->replace($tagArr['value']);
	}

	public function replace($params): string
	{
		return '<?php echo $this->getDataFromSelector(\'' . $params . '\'); ?>';
	}
}