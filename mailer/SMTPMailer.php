<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\mailer;

use Exception;
use framework\common\StringUtils;
use RuntimeException;
use Throwable;

class SMTPMailer extends AbstractMailer
{
    private(set) string $lastReply = '';
    private(set) array $log = [];
    /** @var resource $stream */
    private $stream;

    public function __construct(
        private readonly string $hostName,
        private readonly string $smtpUserName,
        private readonly string $smtpPassword,
        private readonly int $port = 587,
        private readonly bool $useTls = true
    ) {
    }

    public function headerHasTo(): bool
    {
        return true;
    }

    public function headerHasSubject(): bool
    {
        return true;
    }

    public function getMaxLineLength(): int
    {
        return MailerConstants::MAX_LINE_LENGTH;
    }

    public function sendMail(
        AbstractMail $abstractMail,
        MailMimeHeader $mailMimeHeader,
        MailMimeBody $mailMimeBody
    ): void {
        $this->stream = fsockopen(
            hostname: $this->hostName,
            port: $this->port,
            error_code: $enum,
            error_message: $estr,
            timeout: 30
        );
        if ($this->stream === false) {
            throw new RuntimeException(message: 'Socket connection error: ' . $this->hostName);
        }
        $this->checkResponse(
            expectedCode: 220,
            commandTimeout: 300
        );
        $serverName = $this->getServerName();
        $this->sendCommandEHLO(serverName: $serverName);
        if ($this->useTls) {
            $this->sendCommandSTARTTLS();
            stream_socket_enable_crypto(
                stream: $this->stream,
                enable: true,
                crypto_method: STREAM_CRYPTO_METHOD_TLS_CLIENT
            );
            $this->sendCommandEHLO(serverName: $serverName);
        }
        if ($this->smtpUserName !== '') {
            $this->sendCommandAuthLogin();
            $this->sendCommandSmtpUserName();
            $this->sendCommandSmtpPassword();
        }
        $this->sendCommandMailFrom(sender: $abstractMail->sender);
        foreach (
            $abstractMail->mailerAddressCollection->list(
                mailerAddressKindEnum: MailerAddressKindEnum::KIND_TO
            ) as $mailerAddress
        ) {
            $this->sendCommandRecipient(recipient: $mailerAddress);
        }
        foreach (
            $abstractMail->mailerAddressCollection->list(
                mailerAddressKindEnum: MailerAddressKindEnum::KIND_CC
            ) as $mailerAddress
        ) {
            $this->sendCommandRecipient(recipient: $mailerAddress);
        }
        foreach (
            $abstractMail->mailerAddressCollection->list(
                mailerAddressKindEnum: MailerAddressKindEnum::KIND_BCC
            ) as $mailerAddress
        ) {
            $this->sendCommandRecipient(recipient: $mailerAddress);
        }
        $this->sendData(
            data: implode(
                separator: StringUtils::IMPLODE_DEFAULT_SEPARATOR,
                array: [
                    $mailMimeHeader->getMimeHeader(),
                    MailerConstants::CRLF,
                    MailerConstants::CRLF,
                    $mailMimeBody->getMimeBody(),
                ]
            )
        );
        $this->sendCommandQuit();
        $this->close();
    }

    private function checkResponse(
        int $expectedCode,
        int $commandTimeout // https://www.rfc-editor.org/rfc/rfc2821#section-4.5.3.2
    ): void
    {
        stream_set_timeout(
            stream: $this->stream,
            seconds: $commandTimeout
        );
        $this->lastReply = $this->getLines(commandTimeout: $commandTimeout);
        if ($this->lastReply === '') {
            fclose(stream: $this->stream);
            throw new RuntimeException(message: 'Empty response');
        }
        if (
            preg_match(
                pattern: '/^(\d{3})[ -](?:(\d\.\d\.\d{1,2}) )?/',
                subject: $this->lastReply,
                matches: $matches
            ) === 1
        ) {
            $responseCode = (int)$matches[1];
        } else {
            $responseCode = (int)substr(
                string: $this->lastReply,
                offset: 0,
                length: 3
            );
        }
        if ($responseCode === $expectedCode) {
            return;
        }
        fclose(stream: $this->stream);
        throw new RuntimeException(message: 'Unexpected server response code ' . $responseCode);
    }

    private function getLines(int $commandTimeout): string
    {
        if (!is_resource(value: $this->stream)) {
            return '';
        }
        $data = '';
        $endTime = time() + $commandTimeout;
        $selectRead = [$this->stream];
        $selectWrite = null;
        while (
            is_resource(value: $this->stream)
            && !feof(stream: $this->stream)
        ) {
            try {
                stream_select(
                    read: $selectRead,
                    write: $selectWrite,
                    except: $selectWrite,
                    seconds: $commandTimeout
                );
            } catch (Throwable $throwable) {
                if (str_contains(haystack: $throwable->getMessage(), needle: 'interrupted system call')) {
                    continue;
                }
                throw $throwable;
            }
            $str = fgets(
                stream: $this->stream,
                length: 512 // https://www.rfc-editor.org/rfc/rfc5321#section-4.5.3.1.5
            );
            $this->log[] = $str;
            $data .= $str;
            //If response is only 3 chars (not valid, but RFC5321 S4.2 says it must be handled),
            //or 4th character is a space or a line break char, we are done reading, break the loop.
            if (in_array(
                needle: substr(string: $str, offset: 3, length: 1),
                haystack: [
                    '',
                    ' ',
                    "\r",
                    "\n",
                ]
            )) {
                break;
            }
            $info = stream_get_meta_data(stream: $this->stream);
            if ($info['timed_out']) {
                throw new RuntimeException(message: 'Server timeout');
            }
            if ($endTime && time() > $endTime) {
                throw new RuntimeException(message: 'Time limit reached (' . $commandTimeout . ' seconds)');
            }
        }

        return $data;
    }

    private function sendCommandEHLO(string $serverName): void
    {
        $this->sendCommand(
            command: 'EHLO ' . $serverName,
            expectedResponseCode: 250,
            commandTimeout: 10
        );
    }

    private function sendCommand(
        string $command,
        int $expectedResponseCode,
        int $commandTimeout
    ): void {
        if (!$this->isConnected()) {
            throw new Exception(message: 'Tried to send command without being connected');
        }
        if (
            str_contains(haystack: $command, needle: "\n")
            || str_contains(haystack: $command, needle: "\r")
        ) {
            throw new Exception(message: 'Command contained line breaks');
        }
        $this->sendRawDataToServer(data: $command);
        $this->checkResponse(
            expectedCode: $expectedResponseCode,
            commandTimeout: $commandTimeout
        );
    }

    private function isConnected(): bool
    {
        if (!is_resource(value: $this->stream)) {
            return false;
        }
        $sock_status = stream_get_meta_data(stream: $this->stream);
        if ($sock_status['eof']) {
            return false;
        }

        return true;
    }

    private function sendRawDataToServer(string $data): void
    {
        $this->log[] = $data;
        fwrite(stream: $this->stream, data: $data . MailerConstants::CRLF);
    }

    private function sendCommandSTARTTLS(): void
    {
        $this->sendCommand(
            command: 'STARTTLS',
            expectedResponseCode: 220,
            commandTimeout: 10
        );
    }

    private function sendCommandAuthLogin(): void
    {
        $this->sendCommand(
            command: 'AUTH LOGIN',
            expectedResponseCode: 334,
            commandTimeout: 10
        );
    }

    private function sendCommandSmtpUserName(): void
    {
        $this->sendCommand(
            command: base64_encode(string: $this->smtpUserName),
            expectedResponseCode: 334,
            commandTimeout: 10
        );
    }

    private function sendCommandSmtpPassword(): void
    {
        $this->sendCommand(
            command: base64_encode(string: $this->smtpPassword),
            expectedResponseCode: 235,
            commandTimeout: 10
        );
    }

    private function sendCommandMailFrom(MailerAddress $sender): void
    {
        $this->sendCommand(
            command: 'MAIL FROM: <' . $sender->getPunyEncodedEmail() . '>',
            expectedResponseCode: 250,
            commandTimeout: 300
        );
    }

    private function sendCommandRecipient(MailerAddress $recipient): void
    {
        $this->sendCommand(
            command: 'RCPT TO: <' . $recipient->getPunyEncodedEmail() . '>',
            expectedResponseCode: 250,
            commandTimeout: 300
        );
    }

    /**
     * Send an SMTP DATA command.
     * Issues a data command and sends the msg_data to the server,
     * finalizing the mail transaction. $msg_data is the message
     * that is to be sent with the headers. Each header needs to be
     * on a single line followed by a <CRLF> with the message headers
     * and the message body being separated by an additional <CRLF>.
     * Implements RFC 821: DATA <CRLF>.
     *
     * @param string $data Message data to send
     */
    public function sendData(string $data): void
    {
        $this->sendCommandDataStart();
        /**
         * The server is ready to accept data!
         * According to rfc821 we should not send more than 1000 characters on a single line (including the LE)
         * so we will break the data up into lines by \r and/or \n then if needed we will break each of those into
         * smaller lines to fit within the limit.
         * We will also look for lines that start with a '.' and prepend an additional '.'.
         * NOTE: this does not count towards line-length limit.
         */

        // Normalize line breaks before exploding
        $lines = explode(
            separator: "\n",
            string: str_replace(
                search: [
                    "\r\n",
                    "\r",
                ],
                replace: "\n",
                subject: $data
            )
        );

        /**
         * To distinguish between a complete RFC822 message and a plain message body, we check if the first field
         * of the first line (':' separated) does not contain a space then it _should_ be a header, and we will
         * process all lines before a blank line as headers.
         */
        $field = substr(
            string: $lines[0],
            offset: 0,
            length: strpos(
                haystack: $lines[0],
                needle: ':'
            )
        );
        $in_headers = false;
        if (
            $field != ''
            && !str_contains(haystack: $field, needle: ' ')
        ) {
            $in_headers = true;
        }
        foreach ($lines as $line) {
            $lines_out = [];
            if ($in_headers && $line === '') {
                $in_headers = false;
            }
            // Break this line up into several smaller lines if it's too long
            while (strlen(string: $line) > MailerConstants::MAX_LINE_LENGTH) {
                // Working backwards, try to find a space within the last MAX_LINE_LENGTH chars of the line to break on
                // so to avoid breaking in the middle of a word
                $pos = strrpos(
                    haystack: substr(
                        string: $line,
                        offset: 0,
                        length: MailerConstants::MAX_LINE_LENGTH
                    ),
                    needle: ' '
                );
                if ($pos === false || $pos === 0) {
                    // No nice break found, add a hard break
                    $pos = MailerConstants::MAX_LINE_LENGTH - 1;
                    $lines_out[] = substr(string: $line, offset: 0, length: $pos);
                    $line = substr(string: $line, offset: $pos);
                } else {
                    // Break at the found point
                    $lines_out[] = substr(string: $line, offset: 0, length: $pos);
                    // Move along by the amount we dealt with
                    $line = substr(string: $line, offset: $pos + 1);
                }
                // If processing headers add a LWSP-char to the front of new line RFC822 section 3.1.1
                if ($in_headers) {
                    $line = "\t" . $line;
                }
            }
            $lines_out[] = $line;

            // Send the lines to the server
            foreach ($lines_out as $line_out) {
                // Dot-stuffing as per RFC5321 section 4.5.2
                // https://tools.ietf.org/html/rfc5321#section-4.5.2
                if (str_starts_with(haystack: $line, needle: '.')) {
                    $line_out = '.' . $line_out;
                }
                $this->sendRawDataToServer(data: $line_out);
            }
        }
        $this->sendCommandDataEnd();
    }

    private function sendCommandDataStart(): void
    {
        $this->sendCommand(
            command: 'DATA',
            expectedResponseCode: 354,
            commandTimeout: 120
        );
    }

    private function sendCommandDataEnd(): void
    {
        $this->sendCommand(
            command: '.',
            expectedResponseCode: 250,
            commandTimeout: 600
        );
    }

    private function sendCommandQuit(): void
    {
        $this->sendCommand(
            command: 'QUIT',
            expectedResponseCode: 221,
            commandTimeout: 60
        );
    }

    private function close(): void
    {
        if (is_resource(value: $this->stream)) {
            fclose(stream: $this->stream);
            $this->stream = null;
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}