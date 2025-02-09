<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\db;

class DbQueryLogItem
{
	private float $start;
	private ?float $end = null;
	private string $sqlQuery;
	private array $params;

	public function __construct(string $sqlQuery, array $params)
	{
		$this->start = microtime(true);
		$this->sqlQuery = $sqlQuery;
		$this->params = $params;
	}

	public function confirmFinishedExecution(): void
	{
		$this->end = microtime(true);
	}

	public function getExecutionTime(): float
	{
		return $this->end - $this->start;
	}

	public function getSqlQuery(): string
	{
		return $this->sqlQuery;
	}

	public function getParams(): array
	{
		return $this->params;
	}
}