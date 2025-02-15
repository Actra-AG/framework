<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\html;

class HtmlText extends HtmlElement
{
    private string $textContent;
    private bool $isEncodedForRendering;

    private function __construct(string $textContent, bool $isEncodedForRendering)
    {
        $this->textContent = $textContent;
        $this->isEncodedForRendering = $isEncodedForRendering;
        parent::__construct('htmlText');
    }

    public static function encoded(string $textContent): HtmlText
    {
        return new HtmlText(textContent: $textContent, isEncodedForRendering: true);
    }

    public static function unencoded(string $textContent): HtmlText
    {
        return new HtmlText(textContent: $textContent, isEncodedForRendering: false);
    }

    /**
     * Generate the "html-code" for this Text-Element to be used for output
     *
     * @return string Generated html-code
     */
    public function render(): string
    {
        return $this->isEncodedForRendering ? $this->textContent : HtmlEncoder::encode(value: $this->textContent);
    }
}