<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\db;

class DbQueryLogItem
{
    private float $start;
    private ?float $end = null;

    public function __construct(
        private(set) readonly string $sqlQuery,
        private(set) readonly array $params
    ) {
        $this->start = microtime(as_float: true);
    }

    public function confirmFinishedExecution(): void
    {
        $this->end = microtime(as_float: true);
    }

    public function getExecutionTime(): float
    {
        return $this->end - $this->start;
    }
}