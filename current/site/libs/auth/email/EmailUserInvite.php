<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland - www.actra.ch
 */

namespace site\libs\auth\email;

use site\libs\auth\db\DbAuthUserItem;
use site\libs\email\Mailer;

class EmailUserInvite
{
    public static function send(
        DbAuthUserItem $dbAuthUserItem,
        string $subject,
        string $message
    ): void {
        Mailer::sendOutgoingTextMail(
            recipient: $dbAuthUserItem->email,
            subject: $subject,
            textBody: $message
        );
    }
}