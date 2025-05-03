<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\mailer;

class MailMailer extends AbstractMailer
{
    public function headerHasTo(): bool
    {
        return false;
    }

    public function headerHasSubject(): bool
    {
        return false;
    }

    public function sendMail(
        AbstractMail $abstractMail,
        MailMimeHeader $mailMimeHeader,
        MailMimeBody $mailMimeBody
    ): void {
        $senderEmail = $abstractMail->sender->getPunyEncodedEmail();
        $result = mail(
            to: $abstractMail->mailerAddressCollection->listAsCommaSeparatedString(
                mailerAddressKindEnum: MailerAddressKindEnum::KIND_TO,
                maxLineLength: $this->getMaxLineLength(),
                defaultCharSet: $abstractMail->charSet
            ),
            subject: $abstractMail->getSubjectForHeader(maxLineLength: $this->getMaxLineLength()),
            message: $mailMimeBody->getMimeBody(),
            additional_headers: $mailMimeHeader->getMimeHeader() . MailerConstants::CRLF . MailerConstants::CRLF,
            additional_params: MailerFunctions::isShellSafe(string: $senderEmail) ? '-f' . $senderEmail : ''
        );
        if ($result === false) {
            throw new MailerException(message: 'Could not instantiate mail function.');
        }
    }

    public function getMaxLineLength(): int
    {
        return MailerConstants::MAIL_MAX_LINE_LENGTH;
    }
}