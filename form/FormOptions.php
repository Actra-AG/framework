<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form;

use framework\html\HtmlText;

class FormOptions
{
    /** @var HtmlText[] */
    private array $data = [];

    public function __construct()
    {
    }

    public function addItem(string $key, HtmlText $htmlText): void
    {
        $this->data[$key] = $htmlText;
    }

    public function exists(string $key): bool
    {
        return array_key_exists(key: $key, array: $this->data);
    }

    /**
     * @return HtmlText[]
     */
    public function getData(): array
    {
        return $this->data;
    }
}