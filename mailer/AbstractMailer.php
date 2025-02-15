<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\mailer;

abstract class AbstractMailer
{
    abstract public function headerHasTo(): bool;

    abstract public function headerHasSubject(): bool;

    abstract public function getMaxLineLength(): int;

    abstract public function sendMail(
        AbstractMail $abstractMail,
        MailMimeHeader $mailMimeHeader,
        MailMimeBody $mailMimeBody
    ): void;

    public function getServerName(): string
    {
        return gethostbyaddr(ip: $_SERVER['SERVER_ADDR']);
    }
}