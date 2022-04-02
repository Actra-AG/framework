<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\core;

use Exception;
use framework\datacheck\Sanitizer;
use framework\html\HtmlDocument;
use LogicException;

class ContentHandler
{
	private static ?ContentHandler $instance = null;
	private Core $core;
	private int $httpStatusCode = HttpStatusCodes::HTTP_OK;
	private string $content = '';
	private string $contentType = HttpResponse::TYPE_HTML;
	private bool $suppressCspHeader = false;
	private bool $isInitialized = false;

	public static function getInstance(Core $core): ContentHandler
	{
		if (is_null(ContentHandler::$instance)) {
			ContentHandler::$instance = new ContentHandler($core);
		}

		return ContentHandler::$instance;
	}

	private function __construct(Core $core)
	{
		$this->core = $core;
		$requestHandler = RequestHandler::getInstance();

		$defaultContentType = Sanitizer::trimmedString($requestHandler->getContentType());
		if ($defaultContentType !== '') {
			$this->contentType = $defaultContentType;
		}
	}

	public function setContentType(string $contentType): void
	{
		if (!in_array($contentType, HttpResponse::CONTENT_TYPES_WITH_CHARSET)) {
			throw new Exception('Unknown contentType: ' . $contentType);
		}
		$this->contentType = $contentType;
	}

	public function getContentType(): string
	{
		return $this->contentType;
	}

	public function init(): void
	{
		if ($this->isInitialized) {
			throw new LogicException('ContentHandler is already initialized');
		}
		$this->isInitialized = true;
		$core = $this->core;

		ob_start();
		ob_implicit_flush(false);

		$this->loadLocalizedText($core->getLocaleHandler());
		$viewClass = $this->getViewClass();
		if (!$this->hasContent() && !is_null(value: $viewClass)) {
			$viewClass->execute();
		}
		if (!$this->hasContent() && $this->contentType === HttpResponse::TYPE_HTML) {
			$this->setContent(HtmlDocument::getInstance($core)->render());
		}
		$outputBufferContents = trim(string: ob_get_clean());
		if ($outputBufferContents !== '') {
			$this->setContent($outputBufferContents);
		}
	}

	public function setContent(string $contentString): void
	{
		if ($this->hasContent()) {
			throw new LogicException('Content is already set. You are not allowed to overwrite it.');
		}
		$this->content = $contentString;
	}

	public function setHttpStatusCode(int $httpStatusCode): void
	{
		$this->httpStatusCode = $httpStatusCode;
	}

	public function getHttpStatusCode(): int
	{
		return $this->httpStatusCode;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	private function loadLocalizedText(LocaleHandler $localeHandler): void
	{
		$requestHandler = RequestHandler::getInstance();
		$dir = $requestHandler->getViewDirectory() . 'language/' . $requestHandler->getLanguage() . '/';
		if (!is_dir($dir)) {
			return;
		}

		$langGlobal = $dir . 'global.lang.php';

		if (file_exists($langGlobal)) {
			$localeHandler->loadLanguageFile($langGlobal);
		}

		$langFile = $dir . $requestHandler->getFileTitle() . '.lang.php';
		if (file_exists($langFile)) {
			$localeHandler->loadLanguageFile($langFile);
		}
	}

	private function getViewClass(): ?BaseView
	{
		$requestHandler = RequestHandler::getInstance();
		$phpClassName = 'site\\view\\' . $requestHandler->getViewGroup() . '\\php\\';
		if (!is_null(value: $requestHandler->getFileGroup())) {
			$phpClassName .= $requestHandler->getFileGroup() . '\\';
		}
		$phpClassName .= $requestHandler->getFileTitle();
		if (!class_exists(class: $phpClassName)) {
			return null;
		}
		if (!is_subclass_of(object_or_class: $phpClassName, class: BaseView::class)) {
			throw new Exception(message: 'The class ' . $phpClassName . ' must extend ' . BaseView::class . '.');
		}

		return new $phpClassName($this->core);
	}

	public function hasContent(): bool
	{
		return trim(string: $this->content) !== '';
	}

	public function suppressCspHeader(): void
	{
		$this->suppressCspHeader = true;
	}

	public function isSuppressCspHeader(): bool
	{
		return $this->suppressCspHeader;
	}
}