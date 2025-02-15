<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\response;

abstract class HttpResponseContent
{
    protected function __construct(private(set) readonly string $content)
    {
    }
}