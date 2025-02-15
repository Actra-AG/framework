<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\exception;

use Exception;

class PhpException extends Exception
{
    public function __construct(string $message, int $code, string $file, int $line)
    {
        parent::__construct(message: $message, code: $code);
        $this->file = $file;
        $this->line = $line;
    }
}