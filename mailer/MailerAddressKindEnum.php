<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\mailer;

enum MailerAddressKindEnum: string
{
	case KIND_SENDER = 'Sender';
	case KIND_FROM = 'From';
	case KIND_CONFIRM_READING_TO = 'ConfirmReadingTo';
	case KIND_TO = 'To';
	case KIND_CC = 'Cc';
	case KIND_BCC = 'Bcc';
	case KIND_REPLY_TO = 'Reply-To';
}