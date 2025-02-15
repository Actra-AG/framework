<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\template\template;

use Exception;

abstract class TemplateTag
{
    public function __construct()
    {
        if (($this instanceof TagNode) === false && ($this instanceof TagInline) === false) {
            throw new Exception(
                'The class "' . get_class(
                    $this
                ) . '" does not implement the class "TagNode" or "TagInline" and is so recognized as an illegal class for a custom tag."'
            );
        }
    }
}