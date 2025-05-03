<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\session;

use Exception;
use framework\core\EnvironmentSettingsModel;
use framework\core\HttpRequest;
use framework\core\Language;
use framework\exception\UnauthorizedException;
use LogicException;
use SessionHandler;
use Throwable;

abstract class AbstractSessionHandler extends SessionHandler
{
    private const string SESSION_CREATED_INDICATOR = 'sessionCreated';
    private const string TRUSTED_REMOTE_ADDRESS_INDICATOR = 'trustedRemoteAddress';
    private const string TRUSTED_USER_AGENT_INDICATOR = 'trustedUserAgent';
    private const string LAST_ACTIVITY_INDICATOR = 'lastActivity';
    private const string PREFERRED_LANGUAGE_INDICATOR = 'preferredLanguage';
    private static null|false|AbstractSessionHandler $abstractSessionHandler = null;
    private int $currentTime;
    private ?string $ID = null;
    private ?string $name = null;
    private string $clientRemoteAddress;
    private string $clientUserAgent;
    private ?string $fingerprint = null;

    protected function __construct(private readonly SessionSettingsModel $sessionSettingsModel)
    {
        $this->currentTime = time();
        $this->clientRemoteAddress = HttpRequest::getRemoteAddress();
        $this->clientUserAgent = HttpRequest::getUserAgent();

        $this->start();
    }

    private function start(): void
    {
        $sessionSettingsModel = $this->sessionSettingsModel;
        $this->setDefaultConfigurationOptions(maxLifeTime: $sessionSettingsModel->maxLifeTime);
        $this->setDefaultSecuritySettings(isSameSiteStrict: $sessionSettingsModel->isSameSiteStrict);
        $this->setSessionName(individualName: $sessionSettingsModel->individualName);
        $this->executePreStartActions();
        session_set_save_handler( // Named parameters are not supported for alternative prototypes: https://github.com/php/php-src/issues/17263
            $this,
            true
        );
        try {
            session_start(options: [
                'use_strict_mode' => true,
            ]);
        } catch (Throwable $throwable) {
            if (str_contains(haystack: $throwable->getMessage(), needle: 'Permission denied')) {
                throw new UnauthorizedException();
            }
            throw $throwable;
        }
        if (!$this->isSessionCreated()) {
            $this->initDefaultSessionData(destroyCurrentSessionData: false);
        } else {
            if ($this->getTrustedRemoteAddress() !== $this->clientRemoteAddress || $this->getTrustedUserAgent(
                ) !== $this->clientUserAgent) {
                $this->initDefaultSessionData(destroyCurrentSessionData: true);
            } else {
                if ($this->isSessionExpired()) {
                    // Real session lifetime and regeneration after maxLifeTime
                    // See: http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes/1270960#1270960
                    $this->initDefaultSessionData(destroyCurrentSessionData: true);
                } else {
                    if ($this->isSessionOlderThan30Minutes()) {
                        $this->regenerateID();
                    }
                }
            }
        }

        $this->setLastAction();
    }

    private function setDefaultConfigurationOptions(?int $maxLifeTime): void
    {
        if (!is_null(value: $maxLifeTime)) {
            @ini_set(option: 'session.gc_maxlifetime', value: $maxLifeTime);
        }
    }

    private function setDefaultSecuritySettings(bool $isSameSiteStrict): void
    {
        // Set security related configuration options,
        // See https://php.net/manual/en/session.configuration.php
        // -------------------------------------------
        // Send cookies only over HTTPS
        ini_set(option: 'session.cookie_secure', value: true);
        // Do not allow JS to access cookie vars (helps to reduce identity theft through XSS attacks)
        ini_set(option: 'session.cookie_httponly', value: true);
        // Prevent session fixation; very recommended
        ini_set(option: 'session.use_strict_mode', value: true);

        // Prevent cross domain information leakage
        // See https://www.thinktecture.com/de/identity/samesite/samesite-in-a-nutshell/ for further explanations
        ini_set(option: 'session.cookie_samesite', value: $isSameSiteStrict ? 'Strict' : 'Lax');
    }

    private function setSessionName(string $individualName): void
    {
        if ($individualName !== '') {
            $sessionName = $individualName;

            // Overwrite session id in cookie when provided by get-Parameter
            $requestedSessionID = HttpRequest::getInputString(keyName: $sessionName);
            if (!empty($requestedSessionID) && isset($_COOKIE[$sessionName]) && $_COOKIE[$sessionName] !== $requestedSessionID) {
                $_COOKIE[$sessionName] = $requestedSessionID;
                session_id(id: $requestedSessionID);
            }

            session_name(name: $sessionName);
        }

        // Just generate new session id, if current from cookie contains illegal characters
        // Inspired from http://stackoverflow.com/questions/32898857/session-start-issues-regarding-illegal-characters-empty-session-id-and-failed
        $sessionName = session_name();
        if (isset($_COOKIE[$sessionName]) && $this->checkSessionIdAgainstSidBitsPerChar(
                $_COOKIE[$sessionName],
                ini_get(option: 'session.sid_bits_per_character')
            ) === false) {
            unset($_COOKIE[$sessionName]);
        }
    }

    /**
     * Checks session id against valid characters based on the session.sid_bits_per_character ini setting
     * (http://php.net/manual/en/session.configuration.php#ini.session.sid-bits-per-character)
     *
     * @param string $sessionId The session id to check (for example cookie or get value)
     * @param int $sidBitsPerChar The session.sid_bits_per_character value (4, 5 or 6)
     *
     * @return bool Returns true if session_id is valid or false if not
     */
    protected function checkSessionIdAgainstSidBitsPerChar(string $sessionId, int $sidBitsPerChar): bool
    {
        if ($sidBitsPerChar == 4 && preg_match(pattern: '/^[a-f\d]+$/', subject: $sessionId) === 0) {
            return false;
        }

        if ($sidBitsPerChar == 5 && preg_match(pattern: '/^[a-v\d]+$/', subject: $sessionId) === 0) {
            return false;
        }

        if ($sidBitsPerChar == 6 && preg_match(pattern: '/^[A-Za-z\d-,]+$/i', subject: $sessionId) === 0) {
            return false;
        }

        return true;
    }

    abstract protected function executePreStartActions(): void;

    private function isSessionCreated(): bool
    {
        return array_key_exists(key: AbstractSessionHandler::SESSION_CREATED_INDICATOR, array: $_SESSION);
    }

    private function initDefaultSessionData(bool $destroyCurrentSessionData): void
    {
        if ($destroyCurrentSessionData) {
            try {
                if (ini_get(option: 'session.use_cookies')) {
                    $params = session_get_cookie_params();
                    setcookie(
                        session_name(),
                        '',
                        time() - 42000,
                        $params['path'],
                        $params['domain'],
                        $params['secure'],
                        $params['httponly']
                    );
                }
                session_destroy();
                session_start(options: [
                    'use_strict_mode' => true,
                ]);
                session_regenerate_id();
                $this->ID = session_id();
            } catch (Throwable $throwable) {
                if (!str_contains(haystack: $throwable->getMessage(), needle: 'Session object destruction failed')) {
                    throw $throwable;
                }
            }
        }
        $this->setSessionCreated();
        $this->setTrustedRemoteAddress();
        $this->setTrustedUserAgent();
    }

    private function setSessionCreated(): void
    {
        $_SESSION[AbstractSessionHandler::SESSION_CREATED_INDICATOR] = $this->currentTime;
    }

    private function setTrustedRemoteAddress(): void
    {
        $_SESSION[AbstractSessionHandler::TRUSTED_REMOTE_ADDRESS_INDICATOR] = $this->clientRemoteAddress;
    }

    public function setTrustedUserAgent(): void
    {
        $_SESSION[AbstractSessionHandler::TRUSTED_USER_AGENT_INDICATOR] = $this->clientUserAgent;
    }

    public function getTrustedRemoteAddress(): string
    {
        return $_SESSION[AbstractSessionHandler::TRUSTED_REMOTE_ADDRESS_INDICATOR];
    }

    public function getTrustedUserAgent(): string
    {
        return $_SESSION[AbstractSessionHandler::TRUSTED_USER_AGENT_INDICATOR];
    }

    private function isSessionExpired(): bool
    {
        return (
            !is_null(value: $this->sessionSettingsModel->maxLifeTime)
            && array_key_exists(key: AbstractSessionHandler::LAST_ACTIVITY_INDICATOR, array: $_SESSION)
            && ($this->currentTime - $_SESSION[AbstractSessionHandler::LAST_ACTIVITY_INDICATOR] > $this->sessionSettingsModel->maxLifeTime)
        );
    }

    private function isSessionOlderThan30Minutes(): bool
    {
        return ($this->currentTime - $this->getSessionCreated() > 1800);
    }

    public function getSessionCreated(): int
    {
        return $_SESSION[AbstractSessionHandler::SESSION_CREATED_INDICATOR];
    }

    public function regenerateID(): void
    {
        session_regenerate_id();
        $this->ID = session_id();
        $this->setSessionCreated();
    }

    private function setLastAction(): void
    {
        $_SESSION[AbstractSessionHandler::LAST_ACTIVITY_INDICATOR] = $this->currentTime;
    }

    public static function register(false|AbstractSessionHandler $individualSessionHandler): void
    {
        if (!is_null(value: AbstractSessionHandler::$abstractSessionHandler)) {
            throw new LogicException(message: 'SessionHandler handler is already registered.');
        }
        AbstractSessionHandler::$abstractSessionHandler = $individualSessionHandler;
    }

    public static function enabled(): bool
    {
        return (array_key_exists(
            key: '_SESSION',
            array: $GLOBALS
        ));
    }

    public static function getSessionHandler(): AbstractSessionHandler
    {
        return AbstractSessionHandler::$abstractSessionHandler;
    }

    public function getName(): string
    {
        if (is_null(value: $this->name)) {
            $this->name = session_name();
        }

        return $this->name;
    }

    public function getFingerprint(): string
    {
        if (is_null(value: $this->fingerprint)) {
            $this->fingerprint = hash(
                algo: 'sha256',
                data: $this->getID() . $this->clientUserAgent
            );
        }

        return $this->fingerprint;
    }

    public function getID(): string
    {
        if (is_null(value: $this->ID)) {
            $this->ID = session_id();
        }

        return $this->ID;
    }

    public function setPreferredLanguage(Language $language): void
    {
        if (!EnvironmentSettingsModel::get()->availableLanguages->hasLanguage(languageCode: $language->code)) {
            throw new Exception(message: 'The preferred language ' . $language->code . ' is not available');
        }

        $_SESSION[AbstractSessionHandler::PREFERRED_LANGUAGE_INDICATOR] = $language->code;
    }

    public function getPreferredLanguageCode(): ?string
    {
        return array_key_exists(
            key: AbstractSessionHandler::PREFERRED_LANGUAGE_INDICATOR,
            array: $_SESSION
        ) ? $_SESSION[AbstractSessionHandler::PREFERRED_LANGUAGE_INDICATOR] : null;
    }

    public function changeCookieSameSiteToLax(): void
    {
        if ((session_status() === PHP_SESSION_ACTIVE)) {
            // Prevent from "Session cookie parameters cannot be changed when a session is active" exception
            session_write_close();
        }
        session_set_cookie_params(['samesite' => 'Lax']);
        session_start();
    }

    public function changeCookieSameSiteToNone(): void
    {
        if ((session_status() === PHP_SESSION_ACTIVE)) {
            // Prevent from "Session cookie parameters cannot be changed when a session is active" exception
            session_write_close();
        }
        session_set_cookie_params(['samesite' => 'None']);
        session_start();
    }
}