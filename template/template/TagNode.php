<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\template;

use framework\template\htmlparser\ElementNode;

interface TagNode
{
	/**
	 * Replaces the custom tag as node
	 *
	 * @param TemplateEngine $tplEngine
	 * @param ElementNode    $elementNode
	 */
	public function replaceNode(TemplateEngine $tplEngine, ElementNode $elementNode): void;

	public static function getName(): string;

	public static function isElseCompatible(): bool;

	public static function isSelfClosing(): bool;
}