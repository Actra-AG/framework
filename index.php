<?php
/**
 * @author    Christof Moser <christof.moser@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

use framework\Core;
use framework\core\ContentType;
use framework\core\EnvironmentSettingsModel;
use framework\core\Language;
use framework\core\LanguageCollection;
use framework\core\Route;
use framework\core\RouteCollection;
use framework\security\CspPolicySettingsModel;
use site\settings\EnvSettings;

require_once 'framework/Core.php';
$core = new Core(errorLogRecipientEmail: 'error@bsv-buelach.ch');
$languageDe = new Language(code: 'de', locale: 'de_CH');
$core->prepareHttpResponse(
	environmentSettingsModel: new EnvironmentSettingsModel(
		allowedDomains: EnvSettings::ALLOWED_DOMAINS,
		availableLanguages: new LanguageCollection(languages: [$languageDe]),
		debug: EnvSettings::DEBUG,
		copyrightYear: 2006,
		robots: 'index,follow',
		cspPolicySettingsModel: new CspPolicySettingsModel()
	),
	routeCollection: new RouteCollection(routes: [
		new Route(
			path: '/',
			viewGroup: 'frontend',
			defaultFileName: 'start.html',
			isDefaultForLanguage: true,
			defaultContentType: ContentType::createHtml(),
			language: $languageDe,
			acceptedExtension: ContentType::HTML
		),
		/*
		new Route(
			path: '/backend/',
			viewGroup: 'backend',
			defaultFileName: mitglieder::createLink(membershipTabEnum: MembershipTabEnum::ADMIN),
			isDefaultForLanguage: false,
			defaultContentType: ContentType::createHtml(),
			language: $language,
			acceptedExtension: ContentType::HTML
		),
		new Route(
			path: '/cron/',
			viewGroup: 'cron',
			defaultFileName: '',
			isDefaultForLanguage: false,
			defaultContentType: ContentType::createJson(),
			language: $language,
			acceptedExtension: ''
		),
		new Route(
			path: '/postfinance/',
			viewGroup: 'postfinance',
			defaultFileName: '',
			isDefaultForLanguage: false,
			defaultContentType: ContentType::createJson(),
			language: $language,
			acceptedExtension: ''
		),
		new Route(
			path: '${' . BreadCrumbController::ROUTE_VARIABLE . '}/${fileName}',
			viewGroup: 'frontend',
			defaultFileName: 'portrait.html',
			isDefaultForLanguage: false,
			defaultContentType: ContentType::createHtml(),
			language: $language,
			acceptedExtension: ContentType::HTML
		),
		*/
	]),
	individualExceptionHandler: null,
	individualSessionHandler: null
)->sendAndExit();