<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\htmlparser;

class CommentNode extends HtmlNode
{
	public function __construct()
	{
		parent::__construct(nodeType: HtmlNode::COMMENT_NODE);
	}
}