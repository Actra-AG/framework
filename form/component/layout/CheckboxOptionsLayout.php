<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\form\component\layout;

enum CheckboxOptionsLayout: int
{
    case NONE = 0;
    case DEFINITION_LIST = 1;
    case LEGEND_AND_LIST = 2;
    case CHECKBOX_ITEM = 3;
}