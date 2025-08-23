<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\auth;

enum AuthMethod: string
{
    case PASSWORD = 'password';
    case SSO = 'sso';
    case OTP = 'otp';
    case MICROSOFT = 'microsoft';

    public function getSuccessAuthResult(): AuthResult
    {
        return match ($this) {
            AuthMethod::PASSWORD => AuthResult::SUCCESSFUL_PASSWORD_LOGIN,
            AuthMethod::SSO => AuthResult::SUCCESSFUL_SSO_LOGIN,
            AuthMethod::OTP => AuthResult::SUCCESSFUL_OTP_LOGIN,
            AuthMethod::MICROSOFT => AuthResult::SUCCESSFUL_MICROSOFT_LOGIN,
        };
    }
}