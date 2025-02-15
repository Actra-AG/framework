<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\htmlparser;

class DocumentTypeNode extends HtmlNode
{
    public function __construct()
    {
        parent::__construct(nodeType: HtmlNode::DOCUMENT_TYPE_NODE);
    }
}