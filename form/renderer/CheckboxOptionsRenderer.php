<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\component\field\CheckboxOptionsField;

class CheckboxOptionsRenderer extends DefaultOptionsRenderer
{
	public function __construct(CheckboxOptionsField $checkboxOptionsField)
	{
		parent::__construct(optionsField: $checkboxOptionsField, inputFieldType: 'checkbox', acceptMultipleValues: true);
	}
}