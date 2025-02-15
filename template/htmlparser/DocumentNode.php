<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\template\htmlparser;

class DocumentNode extends HtmlNode
{
    public function __construct()
    {
        parent::__construct(nodeType: HtmlNode::DOCUMENT_NODE);
    }
}