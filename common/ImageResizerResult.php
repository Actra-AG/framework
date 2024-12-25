<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
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