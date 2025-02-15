<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\mailer;

class MailerHeaderCollection
{
    /** @var MailerHeader[] */
    private array $items = [];

    public function addItem(MailerHeader $mailerHeader): void
    {
        $this->items[] = $mailerHeader;
    }

    /**
     * @return MailerHeader[]
     */
    public function list(): array
    {
        return $this->items;
    }
}