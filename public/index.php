<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

use actra\backend\ActraBackend;
use actra\backend\settings\MailerSettings;
use actra\yuf\Core;
use actra\yuf\core\ContentType;
use actra\yuf\core\Language;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;
use actra\yuf\db\DbSettingsModel;
use actra\yuf\security\CspPolicySettingsModel;
use app\libs\backend\BackendNavigationItemCollection;
use app\settings\EnvSettings;
use app\settings\ProjectSettings;
use app\view\backend\php\overview;

require __DIR__ . '/../vendor/actra/yuf/src/Core.php';
$core = new Core(
  envFilePath: __DIR__ . '/../.env.php',
  copyrightYear: 2006
);
$deCH = new Language(
  code: 'de',
  locale: 'de_CH'
);
$core->availableLanguages->add(language: $deCH);
$routeCollection = new RouteCollection();
$routeCollection->addRoute(
  route: new Route(
    path: '/',
    viewGroup: 'frontend',
    defaultFileName: 'start.html',
    isDefaultForLanguage: true,
    defaultContentType: ContentType::createHtml(),
    language: $deCH,
    acceptedExtension: ContentType::HTML
  )
);
$routeCollection->addRoute(
  route: new Route(
    path: '/dokumente/${documentID}/${token}/${fileName}',
    viewGroup: 'documents',
    defaultFileName: 'document',
    isDefaultForLanguage: false,
    defaultContentType: ContentType::createHtml(),
    language: $deCH,
    acceptedExtension: null,
    forceFileName: 'document'
  )
);
$routeCollection->addRoute(
  route: new Route(
    path: '/calendar/${eventID}/event.ics',
    viewGroup: 'calendar',
    defaultFileName: 'calendar',
    isDefaultForLanguage: false,
    defaultContentType: ContentType::createHtml(),
    language: $deCH,
    acceptedExtension: null,
    forceFileName: 'calendar'
  )
);
$routeCollection->addRoute(
  route: new Route(
    path: '/backend/',
    viewGroup: 'backend',
    defaultFileName: overview::getPath(),
    isDefaultForLanguage: false,
    defaultContentType: ContentType::createHtml(),
    language: $deCH,
    acceptedExtension: ContentType::HTML
  )
);
require_once __DIR__ . '/../vendor/actra/backend/src/ActraBackend.php';
ActraBackend::init(
  routeCollection: $routeCollection,
  path: '/auth/',
  isDefaultForLanguage: false,
  language: $deCH,
  ipWhitelist: [],
  backendName: ProjectSettings::BACKEND_NAME,
  scriptsHref: '/js/backend/scripts.min.js?v=20260426',
  stylesHref: '/css/backend/styles.min.css?v=20260426',
  dbSettingsModel: new DbSettingsModel(
    identifier: EnvSettings::getDbIdentifier(),
    hostName: EnvSettings::getDbHostname(),
    databaseName: EnvSettings::getDbDatabase(),
    userName: EnvSettings::getDbUsername(),
    password: EnvSettings::getDbPassword(),
    charset: null,
    timeNamesLanguage: 'de_CH',
    sqlSafeUpdates: true
  ),
  mailerSettings: new MailerSettings(
    senderEmail: ProjectSettings::WEBMASTER_EMAIL,
    hostname: EnvSettings::getMailerHostname(),
    username: EnvSettings::getMailerUsername(),
    password: EnvSettings::getMailerPassword(),
    port: EnvSettings::getMailerPort(),
    tls: EnvSettings::getMailerTls(),
    signature: 'Bezirksschützenverband Bülach'
  ),
  navigationItemCollection: new BackendNavigationItemCollection(),
  frontendHref: ProjectSettings::FRONTEND_HREF,
  frontendName: ProjectSettings::FRONTEND_NAME
);
$core->prepareHttpResponse(
  routeCollection: $routeCollection,
  cspPolicySettingsModel: new CspPolicySettingsModel(
    styleSrc: "'self' 'unsafe-inline'",
  )
)->sendAndExit();