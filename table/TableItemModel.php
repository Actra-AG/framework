<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table;

use framework\html\HtmlEncoder;
use stdClass;

readonly class TableItemModel
{
	public array $data;

	public function __construct(stdClass $dataObject)
	{
		$this->data = get_object_vars(object: $dataObject);
	}

	public function getRawValue(string $name): mixed
	{
		return $this->data[$name];
	}

	public function renderValue(string $name, bool $renderNewLines = false): string
	{
		$value = $this->data[$name];
		if (is_null(value: $value)) {
			return '';
		}

		if ($renderNewLines) {
			return nl2br(string: HtmlEncoder::encodeKeepQuotes(value: str_replace(search: '<br>', replace: PHP_EOL, subject: $value)));
		}

		return HtmlEncoder::encodeKeepQuotes(value: $value);
	}
}