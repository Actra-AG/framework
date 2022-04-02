<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\template\htmlparser;

class DocumentTypeNode extends HtmlNode
{
	public function __construct()
	{
		parent::__construct(nodeType: HtmlNode::DOCUMENT_TYPE_NODE);
	}
}