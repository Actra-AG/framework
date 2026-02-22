<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

declare(strict_types=1);

use actra\yuf\Core;
use actra\yuf\core\ContentType;
use actra\yuf\core\Language;
use actra\yuf\core\Route;
use actra\yuf\core\RouteCollection;

require __DIR__ . '/../vendor/actra/yuf/src/Core.php';
$core = new Core(
    envFilePath: __DIR__ . '/../.env.php',
    copyrightYear: 2006
);
$languageDE = new Language(
    code: 'de',
    locale: 'de_CH'
);
$core->availableLanguages->add(language: $languageDE);
$core->prepareHttpResponse(
    routeCollection: new RouteCollection(
        routes: [
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
        ]
    )
)->sendAndExit();