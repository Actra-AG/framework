<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\email;

use actra\backend\libs\email\Mailer;
use app\settings\ProjectSettings;

class EmailToWebmaster
{
    public static function send(
        string $subject,
        string $message
    ): void {
        Mailer::sendTextMail(
            recipient: ProjectSettings::WEBMASTER_EMAIL,
            subject: $subject,
            textBody: $message
        );
    }
}