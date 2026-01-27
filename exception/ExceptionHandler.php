<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\exception;

use framework\Core;
use framework\core\ContentHandler;
use framework\core\ContentType;
use framework\core\EnvironmentSettingsModel;
use framework\core\HttpResponse;
use framework\core\HttpStatusCode;
use framework\core\LocaleHandler;
use framework\core\Logger;
use framework\core\RequestHandler;
use framework\html\HtmlReplacement;
use framework\html\HtmlReplacementCollection;
use framework\html\HtmlSnippet;
use framework\response\HttpErrorResponseContent;
use framework\security\CspNonce;
use framework\security\CsrfToken;
use LogicException;
use Throwable;

class ExceptionHandler
{
    private static ?ExceptionHandler $registeredInstance = null;
    protected ContentType $contentType;

    public function __construct(
        protected readonly HtmlReplacementCollection $htmlReplacementCollection = new HtmlReplacementCollection()
    )
    {
    }

    public static function register(?ExceptionHandler $individualExceptionHandler): void
    {
        if (!is_null(value: ExceptionHandler::$registeredInstance)) {
            throw new LogicException(message: 'ExceptionHandler is already registered.');
        }
        ExceptionHandler::$registeredInstance = is_null(value: $individualExceptionHandler) ? new ExceptionHandler() : $individualExceptionHandler;
        set_exception_handler(callback: [
            ExceptionHandler::$registeredInstance,
            'handleException',
        ]);
    }

    final public function handleException(Throwable $throwable): void
    {
        $this->contentType = ContentHandler::isRegistered() ? ContentHandler::get()->getContentType() : ContentType::createHtml();
        if (EnvironmentSettingsModel::get()->debug) {
            $this->sendDebugHttpResponseAndExit(throwable: $throwable);
        }
        if ($throwable instanceof NotFoundException) {
            $this->sendNotFoundHttpResponseAndExit(throwable: $throwable);
        }
        if ($throwable instanceof UnauthorizedException) {
            $this->sendUnauthorizedHttpResponseAndExit(throwable: $throwable);
        }
        Logger::get()->logException(throwable: $throwable);
        $this->sendDefaultHttpResponseAndExit(throwable: $throwable);
    }

    protected function sendDebugHttpResponseAndExit(Throwable $throwable): void
    {
        $realException = is_null(value: $throwable->getPrevious()) ? $throwable : $throwable->getPrevious();
        $errorCode = $realException->getCode();
        $errorMessage = $realException->getMessage();

        if ($throwable instanceof NotFoundException) {
            $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND;
            $this->htmlReplacementCollection->addEncodedText(
                identifier: 'title',
                content: 'Page not found'
            );
        } elseif ($throwable instanceof UnauthorizedException) {
            $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED;
            $this->htmlReplacementCollection->addEncodedText(
                identifier: 'title',
                content: 'Unauthorized'
            );
        } else {
            $httpStatusCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;
            $this->htmlReplacementCollection->addEncodedText(
                identifier: 'title',
                content: 'Internal Server Error'
            );
        }
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'errorType',
            content: get_class(object: $throwable)
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'errorMessage',
            content: $errorMessage
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'errorFile',
            content: $realException->getFile()
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'errorLine',
            content: $realException->getLine()
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'errorCode',
            content: $realException->getCode()
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'backtrace',
            content: (!$this->contentType->isJson()) ? $realException->getTraceAsString() : $realException->getTrace()
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'vardump_get',
            content: isset($_GET) ? htmlentities(string: var_export(value: $_GET, return: true)) : ''
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'vardump_post',
            content: isset($_POST) ? htmlentities(
                string: var_export(value: $_POST, return: true)
            ) : ''
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'vardump_file',
            content: isset($_FILE) ? htmlentities(
                string: var_export(value: $_FILE, return: true)
            ) : ''
        );
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'vardump_sess',
            content: isset($_SESSION) ? htmlentities(
                string: var_export(
                    value: $_SESSION,
                    return: true
                )
            ) : ''
        );
        $this->sendHttpResponseAndExit(
            httpStatusCode: $httpStatusCode,
            errorMessage: $errorMessage,
            errorCode: $errorCode,
            htmlFileName: 'debug.html'
        );
    }

    final protected function sendHttpResponseAndExit(
        HttpStatusCode $httpStatusCode,
        string         $errorMessage,
        string|int     $errorCode,
        string         $htmlFileName
    ): void
    {
        $environmentSettingsModel = EnvironmentSettingsModel::get();
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'copyright',
            content: $environmentSettingsModel->renderCopyrightYear()
        );
        $contentType = $this->contentType;
        if ($contentType->isJson()) {
            $httpResponse = HttpResponse::createResponseFromString(
                httpStatusCode: $httpStatusCode,
                contentString: HttpErrorResponseContent::createJsonResponseContent(
                    errorMessage: $errorMessage,
                    errorCode: $errorCode,
                    additionalInfo: $this->htmlReplacementCollection->getArrayObject()
                )->content,
                contentType: $contentType
            );
            $httpResponse->sendAndExit();
        }
        if ($contentType->isTxt() || $contentType->isCsv()) {
            $httpResponse = HttpResponse::createResponseFromString(
                httpStatusCode: $httpStatusCode,
                contentString: HttpErrorResponseContent::createTextResponseContent(
                    errorMessage: $errorMessage,
                    errorCode: $errorCode
                )->content,
                contentType: $contentType
            );
            $httpResponse->sendAndExit();
        }
        $httpResponse = HttpResponse::createHtmlResponse(
            httpStatusCode: $httpStatusCode,
            htmlContent: $this->getHtmlContent(
                htmlFileName: $htmlFileName
            ),
            cspPolicySettingsModel: $environmentSettingsModel->cspPolicySettingsModel,
            nonce: CspNonce::get()
        );
        $httpResponse->sendAndExit();
    }

    private function getHtmlContent(string $htmlFileName): string
    {
        $contentPath = Core::get()->errorDocsDirectory . $htmlFileName;
        if (!file_exists(filename: $contentPath)) {
            return 'Missing error html file ' . $contentPath;
        }
        $htmlReplacementCollection = $this->htmlReplacementCollection;
        $requestHandler = RequestHandler::get();
        $htmlReplacementCollection->addEncodedText(
            identifier: 'language',
            content: $requestHandler->language->code
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'langRoot',
            content: $requestHandler->getLanguageRoot()
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'charset',
            content: 'UTF-8'
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'cspNonce',
            content: CspNonce::get()
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'csrfField',
            content: CsrfToken::renderAsHiddenPostField()
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'robots',
            content: 'noindex,nofollow'
        );
        $htmlReplacementCollection->set(
            identifier: 'pageTitle',
            htmlReplacement: $htmlReplacementCollection->has(identifier: 'title') ? $htmlReplacementCollection->get(identifier: 'title') : HtmlReplacement::encodedText(content: 'Error')
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'bodyClassName',
            content: 'body-' . pathinfo(path: $htmlFileName)['filename']
        );
        $htmlReplacementCollection->addEncodedText(
            identifier: 'requestedFileName',
            content: $requestHandler->fileName
        );
        if (
            EnvironmentSettingsModel::get()->availableLanguages->isMultiLang()
            && !LocaleHandler::isRegistered()
        ) {
            $this->loadLocalizedText(requestHandler: $requestHandler);
        }

        return new HtmlSnippet(
            htmlSnippetFilePath: $contentPath,
            replacements: $htmlReplacementCollection
        )->render();
    }

    private function loadLocalizedText(
        RequestHandler $requestHandler
    ): void
    {
        $languageCode = $requestHandler->language->code;
        LocaleHandler::register();
        $defaultRouteForLanguage = $requestHandler->defaultRoutesByLanguage->getRouteForLanguage(
            languageCode: $languageCode
        );
        $dir = $defaultRouteForLanguage->viewDirectory . 'language' . DIRECTORY_SEPARATOR . $languageCode . DIRECTORY_SEPARATOR;
        if (!is_dir(filename: $dir)) {
            return;
        }
        $langGlobal = $dir . 'global.lang.php';
        $locale = LocaleHandler::get();
        if (file_exists(filename: $langGlobal)) {
            $locale->loadLanguageFile(filePath: $langGlobal);
        }
    }

    protected function sendNotFoundHttpResponseAndExit(Throwable $throwable): void
    {
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'title',
            content: 'Page not found'
        );
        $this->sendHttpResponseAndExit(
            httpStatusCode: HttpStatusCode::HTTP_NOT_FOUND,
            errorMessage: $throwable->getMessage(),
            errorCode: $throwable->getCode(),
            htmlFileName: 'notFound.html'
        );
    }

    protected function sendUnauthorizedHttpResponseAndExit(Throwable $throwable): void
    {
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'title',
            content: 'Unauthorized'
        );
        $this->sendHttpResponseAndExit(
            httpStatusCode: HttpStatusCode::HTTP_UNAUTHORIZED,
            errorMessage: $throwable->getMessage(),
            errorCode: $throwable->getCode(),
            htmlFileName: 'unauthorized.html'
        );
    }

    protected function sendDefaultHttpResponseAndExit(Throwable $throwable): void
    {
        $this->htmlReplacementCollection->addEncodedText(
            identifier: 'title',
            content: 'Internal Server Error'
        );
        $this->sendHttpResponseAndExit(
            httpStatusCode: HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR,
            errorMessage: 'Internal Server Error',
            errorCode: $throwable->getCode(),
            htmlFileName: 'default.html'
        );
    }
}