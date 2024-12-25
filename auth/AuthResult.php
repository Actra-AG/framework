<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\auth;

use framework\html\HtmlText;
use LogicException;

enum AuthResult: int
{
	case UNDEFINED = 0;
	case SUCCESSFUL_PASSWORD_LOGIN = 1;
	case ERROR_NO_EMAIL_ADDRESS = 2;
	case ERROR_NO_PASSWORD = 3;
	case ERROR_UNKNOWN_USER_NAME = 4;
	case ERROR_INACTIVE = 5;
	case ERROR_OUT_TRIED = 7;
	case ERROR_WRONG_PASSWORD = 8;
	case SUCCESSFUL_SSO_LOGIN = 10;
	case ERROR_NO_PASSWORD_LOGIN_ACTIVE = 11;
	case FAILED_SSO_LOGIN = 12;

	public function renderErrorMessage(): HtmlText
	{
		return match ($this) {
			AuthResult::ERROR_UNKNOWN_USER_NAME => HtmlText::encoded(textContent: 'Die eingegebenen Zugangsdaten sind ungültig.'),
			AuthResult::ERROR_INACTIVE => HtmlText::encoded(textContent: 'Dieser Zugang ist nicht aktiv.'),
			AuthResult::ERROR_OUT_TRIED => HtmlText::encoded(textContent: 'Bei diesem Konto wurde zu oft das falsche Passwort eingegeben.'),
			AuthResult::ERROR_WRONG_PASSWORD => HtmlText::encoded(textContent: 'Es wurden ungültige Zugangsdaten eingegeben.'),
			AuthResult::ERROR_NO_PASSWORD_LOGIN_ACTIVE => HtmlText::encoded(textContent: 'Die Anmeldung mit Passwort ist für diesen Benutzer nicht aktiviert.'),
			AuthResult::FAILED_SSO_LOGIN => HtmlText::encoded(textContent: 'Das Single Sign-On ist leider fehlgeschlagen.'),
			default => throw new LogicException(message: 'Undefined error message'),
		};
	}

	public function render(): string
	{
		return (match ($this) {
			AuthResult::UNDEFINED => HtmlText::encoded(textContent: 'Unbekannt'),
			AuthResult::ERROR_UNKNOWN_USER_NAME => HtmlText::encoded(textContent: 'Ungültige E-Mail-Adresse'),
			AuthResult::ERROR_NO_EMAIL_ADDRESS => HtmlText::encoded(textContent: 'Keine E-Mail-Adresse'),
			AuthResult::ERROR_NO_PASSWORD => HtmlText::encoded(textContent: 'Kein Passwort'),
			AuthResult::ERROR_INACTIVE => HtmlText::encoded(textContent: 'Zugang inaktiv'),
			AuthResult::ERROR_OUT_TRIED => HtmlText::encoded(textContent: 'Zu viele fehlerhafte Versuche'),
			AuthResult::ERROR_WRONG_PASSWORD => HtmlText::encoded(textContent: 'Falsches Passwort'),
			AuthResult::SUCCESSFUL_PASSWORD_LOGIN => HtmlText::encoded(textContent: 'Passwort-Anmeldung'),
			AuthResult::SUCCESSFUL_SSO_LOGIN => HtmlText::encoded(textContent: 'SSO-Anmeldung'),
			AuthResult::ERROR_NO_PASSWORD_LOGIN_ACTIVE => HtmlText::encoded(textContent: 'Passwort-Anmeldung inaktiv'),
			AuthResult::FAILED_SSO_LOGIN => HtmlText::encoded(textContent: 'SSO fehlgeschlagen'),
		})->render();
	}
}