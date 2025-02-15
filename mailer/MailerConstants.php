<?php
/**
 * Derived work from PHPMailer, reduced to the code needed by this Framework.
 * For original full library, please see:
 *
 * @see       https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 * @author    Marcus Bointon (Synchro/coolbru) <phpmailer@synchromedia.co.uk>
 * @author    Jim Jagielski (jimjag) <jimjag@gmail.com>
 * @author    Andy Prevost (codeworxtech) <codeworxtech@users.sourceforge.net>
 * @author    Brent R. Matzelle (original founder)
 * @author    Actra AG (for derived, reduced code)  - www.actra.ch
 * @copyright 2012 - 2020 Marcus Bointon
 * @copyright 2010 - 2012 Jim Jagielski
 * @copyright 2004 - 2009 Andy Prevost
 * @copyright 2022 Actra AG
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace framework\mailer;

class MailerConstants
{
    public const string CHARSET_ASCII = 'us-ascii';
    public const string CHARSET_UTF8 = 'utf-8';
    public const array CHARSET_LIST = [
        MailerConstants::CHARSET_ASCII,
        MailerConstants::CHARSET_UTF8,
    ];

    public const string ENCODING_7BIT = '7bit';
    public const string ENCODING_8BIT = '8bit';
    public const string ENCODING_BASE64 = 'base64';
    public const string ENCODING_BINARY = 'binary';
    public const string ENCODING_QUOTED_PRINTABLE = 'quoted-printable';
    public const array ENCODING_LIST = [
        MailerConstants::ENCODING_7BIT,
        MailerConstants::ENCODING_8BIT,
        MailerConstants::ENCODING_BASE64,
        MailerConstants::ENCODING_BINARY,
        MailerConstants::ENCODING_QUOTED_PRINTABLE,
    ];

    public const int PRIORITY_HIGH = 1;
    public const int PRIORITY_NORMAL = 3;
    public const int PRIORITY_LOW = 5;
    public const array PRIORITY_LIST = [
        MailerConstants::PRIORITY_HIGH,
        MailerConstants::PRIORITY_NORMAL,
        MailerConstants::PRIORITY_LOW,
    ];

    public const string CONTENT_TYPE_PLAINTEXT = 'text/plain';
    public const string CONTENT_TYPE_TEXT_HTML = 'text/html';
    public const string CONTENT_TYPE_MULTIPART_ALTERNATIVE = 'multipart/alternative';
    public const string CONTENT_TYPE_MULTIPART_MIXED = 'multipart/mixed';
    public const string CONTENT_TYPE_MULTIPART_RELATED = 'multipart/related';

    public const string CRLF = "\r\n";
    public const int MAIL_MAX_LINE_LENGTH = 63; // mail() will sometimes corrupt messages with headers longer than 65 chars
    public const int MAX_LINE_LENGTH = 998; // The maximum line length allowed by RFC 2822 section 2.1.1.
    /**
     * The lower maximum line length allowed by RFC 2822 section 2.1.1.
     * This length does NOT include the line break 76 means that lines will be 77 or 78 chars depending on whether the line break format is LF or CRLF; both
     * are valid.
     */
    public const int STD_LINE_LENGTH = 76;
}