<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

use framework\Core;
use framework\core\ContentType;
use framework\core\EnvironmentSettingsModel;
use framework\core\HttpRequest;
use framework\core\Language;
use framework\core\LanguageCollection;
use framework\core\Logger;
use framework\core\Route;
use framework\core\RouteCollection;
use site\settings\EnvSettings;

require_once 'framework/Core.php';
$core = new Core();
$languageDE = new Language(
    code: 'de',
    locale: 'de_CH'
);
$cspProtocolHost = HttpRequest::getProtocol() . '://' . HttpRequest::getHost();
$core->prepareHttpResponse(
    environmentSettingsModel: new EnvironmentSettingsModel(
        allowedDomains: EnvSettings::ALLOWED_DOMAINS,
        availableLanguages: new LanguageCollection(languages: [
            $languageDE
        ]),
        debug: EnvSettings::DEBUG,
        copyrightYear: 2006,
        robots: 'index,follow',
        cspPolicySettingsModel: null
    ),
    logger: new Logger(
        logEmailRecipient: 'error@bsv-buelach.ch',
        logDirectory: $core->logDirectory
    ),
    routeCollection: new RouteCollection(routes: [
        new Route(
            path: '/',
            viewGroup: 'frontend',
            defaultFileName: 'start.html',
            isDefaultForLanguage: true,
            defaultContentType: ContentType::createHtml(),
            language: $languageDE,
            acceptedExtension: ContentType::HTML
        ),
        new Route(
            path: '/backend/',
            viewGroup: 'backend',
            defaultFileName: 'login.html',
            isDefaultForLanguage: false,
            defaultContentType: ContentType::createHtml(),
            language: $languageDE,
            acceptedExtension: ContentType::HTML
        ),
        new Route(
            path: '/dokumente/',
            viewGroup: 'documents',
            defaultFileName: '',
            isDefaultForLanguage: false,
            defaultContentType: ContentType::createJson(),
            language: $languageDE,
            acceptedExtension: ''
        ),
        new Route(
            path: '/calendar/',
            viewGroup: 'calendar',
            defaultFileName: '',
            isDefaultForLanguage: false,
            defaultContentType: ContentType::createJson(),
            language: $languageDE,
            acceptedExtension: ''
        ),
    ]),
    individualExceptionHandler: null
)->sendAndExit();