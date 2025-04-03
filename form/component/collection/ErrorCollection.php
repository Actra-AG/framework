<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\component\collection;

use framework\html\HtmlText;

class ErrorCollection
{
    /** @var HtmlText[] */
    private array $errors = [];

    public function add(HtmlText $errorMessageObject): void
    {
        $this->errors[] = $errorMessageObject;
    }

    /**
     * @return HtmlText[]
     */
    public function listErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return $this->count() > 0;
    }

    public function count(): int
    {
        return count(value: $this->errors);
    }

    public function getFirstError(): HtmlText
    {
        return current(array: $this->errors);
    }
}