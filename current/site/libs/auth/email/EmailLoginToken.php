<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland - www.actra.ch
 */

namespace site\libs\auth\email;

use site\libs\auth\db\DbAuthUserItem;
use site\libs\email\Mailer;
use site\settings\ProjectSettings;

class EmailLoginToken
{
    public static function send(
        DbAuthUserItem $dbAuthUserItem,
        string $loginCode
    ): void {
        Mailer::sendOutgoingTextMail(
            recipient: $dbAuthUserItem->email,
            subject: 'Backend - ' . $loginCode . ' ist ihr Bestätigungscode',
            textBody: implode(separator: PHP_EOL, array: [
                'Grüezi',
                '',
                'Mit dem nachfolgenden Bestätigungscode können Sie sich ohne Passwort sicher im Backend anmelden:',
                '',
                $loginCode,
                '',
                'Bitte beachten Sie, dass dieser Code nur einmal verwendet werden kann und nach 10 Minuten verfällt.',
                '',
                'Wenn Sie keinen Bestätigungscode für die E-Mail-Adresse ' . $dbAuthUserItem->email . ' angefordert haben, können Sie diese E-Mail ignorieren.',
                '',
                'Freundliche Grüsse',
                '',
                ProjectSettings::EMAIL_SIGNATURE,
            ])
        );
    }
}