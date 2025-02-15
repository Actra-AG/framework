<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

use Exception;
use framework\Core;
use framework\html\HtmlDocument;
use LogicException;

class ContentHandler
{
    private static ?ContentHandler $registeredInstance = null;

    public HttpStatusCode $httpStatusCode = HttpStatusCode::HTTP_OK;
    private(set) bool $suppressCspHeader = false;
    private string $content = '';
    private ContentType $contentType;

    private function __construct()
    {
        if (!is_null(value: ContentHandler::$registeredInstance)) {
            throw new LogicException(message: 'ContentHandler is already registered.');
        }
        ContentHandler::$registeredInstance = $this;
        $this->contentType = RequestHandler::get()->route->defaultContentType;
        ob_start();
        ob_implicit_flush(enable: false);
        $this->loadLocalizedText();
        $viewClass = $this->getViewClass();
        if (
            !$this->hasContent()
            && !is_null(value: $viewClass)
        ) {
            $viewClass->execute();
        }
        if (!$this->hasContent() && $this->contentType->isHtml()) {
            $this->setContent(HtmlDocument::get()->render());
        }
        $outputBufferContents = trim(string: ob_get_clean());
        if ($outputBufferContents !== '') {
            $this->setContent($outputBufferContents);
        }
    }

    public static function get(): ContentHandler
    {
        return ContentHandler::$registeredInstance;
    }

    private function loadLocalizedText(): void
    {
        $request = RequestHandler::get();
        $dir = $request->route->viewDirectory . 'language' . DIRECTORY_SEPARATOR . $request->language->code . DIRECTORY_SEPARATOR;
        if (!is_dir(filename: $dir)) {
            return;
        }
        $langGlobal = $dir . 'global.lang.php';
        $locale = LocaleHandler::get();
        if (file_exists(filename: $langGlobal)) {
            $locale->loadLanguageFile(filePath: $langGlobal);
        }
        $langFile = $dir . $request->fileTitle . '.lang.php';
        if (file_exists(filename: $langFile)) {
            $locale->loadLanguageFile(filePath: $langFile);
        }
    }

    private function getViewClass(): ?BaseView
    {
        $core = Core::get();
        $request = RequestHandler::get();
        $phpClassNameParts = [
            $core->siteDirectoryName,
            $core->viewDirectoryName,
            $request->route->viewGroup,
            'php',
        ];
        if (!is_null(value: $request->fileGroup)) {
            $phpClassNameParts[] = $request->fileGroup;
        }
        $phpClassNameParts[] = $request->fileTitle;
        if (!file_exists(
            filename: $core->documentRoot . implode(
                separator: DIRECTORY_SEPARATOR,
                array: $phpClassNameParts
            ) . '.php'
        )) {
            return null;
        }
        $phpClassName = implode(separator: '\\', array: $phpClassNameParts);
        if (!class_exists(class: $phpClassName)) {
            return null;
        }
        if (!is_subclass_of(object_or_class: $phpClassName, class: BaseView::class)) {
            throw new Exception(message: 'The class ' . $phpClassName . ' must extend ' . BaseView::class . '.');
        }

        return new $phpClassName();
    }

    public function hasContent(): bool
    {
        return trim(string: $this->content) !== '';
    }

    public static function register(): ContentHandler
    {
        return new ContentHandler();
    }

    public static function isRegistered(): bool
    {
        return !is_null(value: ContentHandler::$registeredInstance);
    }

    public function getContentType(): ContentType
    {
        return $this->contentType;
    }

    public function setContentType(ContentType $contentType): void
    {
        if (is_null(value: $contentType->charset)) {
            throw new Exception(message: 'Unknown contentType: ' . $contentType->type);
        }
        $this->contentType = $contentType;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $contentString): void
    {
        if ($this->hasContent()) {
            throw new LogicException('Content is already set. You are not allowed to overwrite it.');
        }
        $this->content = $contentString;
    }

    public function suppressCspHeader(): void
    {
        $this->suppressCspHeader = true;
    }
}