<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\customtags;

use framework\template\htmlparser\ElementNode;
use framework\template\htmlparser\TextNode;
use framework\template\template\TagNode;
use framework\template\template\TemplateEngine;
use framework\template\template\TemplateTag;

class DateTag extends TemplateTag implements TagNode
{
	public static function getName(): string
	{
		return 'date';
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
		$format = $elementNode->getAttribute('format')->getValue();
		$replNode = new TextNode();
		$replNode->content = '<?php echo date(\'' . $format . '\'); ?>';

		$elementNode->parentNode->replaceNode($elementNode, $replNode);
	}
}