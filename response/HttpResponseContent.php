<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\response;

abstract class HttpResponseContent
{
	private string $content;

	protected function __construct(string $content)
	{
		$this->content = $content;
	}

	public function getContent(): string
	{
		return $this->content;
	}
}