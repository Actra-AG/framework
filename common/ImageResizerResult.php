<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\common;

enum ImageResizerResult
{
    case SUCCESS;
    case MISSING_SOURCE_IMAGE;
    case INVALID_SOURCE_IMAGE;
    case CREATE_ORIGINAL_FAILED;
    case CREATE_NEW_FAILED;
}