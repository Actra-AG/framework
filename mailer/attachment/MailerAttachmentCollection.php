<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\mailer\attachment;

use framework\mailer\MailerException;

class MailerAttachmentCollection
{
    /** @var MailerFileAttachment[]|MailerStringAttachment[] */
    private array $items = [];

    public function addItem(MailerFileAttachment|MailerStringAttachment $mailerAttachment): void
    {
        $fileName = $mailerAttachment->fileName;
        if (array_key_exists(key: $fileName, array: $this->items)) {
            throw new MailerException(message: 'Attachment with fileName "' . $fileName . '" already exists.');
        }
        $this->items[$fileName] = $mailerAttachment;
    }

    /**
     * @return MailerFileAttachment[]|MailerStringAttachment[]
     */
    public function list(): array
    {
        return $this->items;
    }

    public function hasInlineImages(): bool
    {
        return array_any($this->items, fn($mailerAttachment) => $mailerAttachment->dispositionInline);
    }

    public function hasAttachments(): bool
    {
        return array_any($this->items, fn($mailerAttachment) => !$mailerAttachment->dispositionInline);
    }
}