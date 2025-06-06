<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\html;

use framework\Core;
use framework\core\EnvironmentSettingsModel;
use framework\core\RequestHandler;
use framework\exception\NotFoundException;
use framework\security\CspNonce;
use framework\security\CsrfToken;
use framework\template\template\DirectoryTemplateCache;
use framework\template\template\TemplateEngine;

class HtmlDocument
{
    private static ?HtmlDocument $instance = null;
    public readonly HtmlReplacementCollection $replacements;
    public string $templateName = 'default';
    public string $contentFileName;
    private array $activeHtmlIds = [];

    private function __construct()
    {
        $request = RequestHandler::get();
        $fileTitle = $request->fileTitle;
        $this->contentFileName = $fileTitle;
        $this->replacements = new HtmlReplacementCollection();
        $environmentSettingsModel = EnvironmentSettingsModel::get();

        $replacements = $this->replacements;
        $replacements->addEncodedText(identifier: 'bodyid', content: 'body_' . $fileTitle);
        $replacements->addEncodedText(identifier: 'language', content: $request->language->code);
        $replacements->addEncodedText(identifier: 'charset', content: 'UTF-8');
        $replacements->addEncodedText(
            identifier: 'copyright',
            content: $environmentSettingsModel->renderCopyrightYear()
        );
        $replacements->addEncodedText(identifier: 'robots', content: $environmentSettingsModel->robots);
        $replacements->addEncodedText(identifier: 'scripts', content: '');
        $replacements->addEncodedText(identifier: 'cspNonce', content: CspNonce::get());
        $replacements->addEncodedText(identifier: 'csrfField', content: CsrfToken::renderAsHiddenPostField());
    }

    public static function get(): HtmlDocument
    {
        if (is_null(value: HtmlDocument::$instance)) {
            HtmlDocument::$instance = new HtmlDocument();
        }

        return HtmlDocument::$instance;
    }

    public function setActiveHtmlId(int $key, string $val): void
    {
        $this->activeHtmlIds[$key] = $val;
    }

    public function isActiveHtmlIdSet(int $key): bool
    {
        return array_key_exists(key: $key, array: $this->activeHtmlIds);
    }

    public function render(): string
    {
        $request = RequestHandler::get();
        $viewDirectory = $request->route->viewDirectory;
        $contentFileDirectory = $viewDirectory . 'html/';
        if (!is_null(value: $request->fileGroup)) {
            $contentFileDirectory .= $request->fileGroup . '/';
        }
        if ($this->contentFileName === '') {
            throw new NotFoundException();
        }
        $fullContentFilePath = $contentFileDirectory . $this->contentFileName . '.html';
        if (!is_file(filename: $fullContentFilePath)) {
            throw new NotFoundException();
        }
        $this->replacements->addEncodedText(identifier: 'this', content: $fullContentFilePath);
        $templateName = $this->templateName;
        $templateFilePath = $viewDirectory . 'templates/' . $templateName . '.html';
        if ($templateName === '' || !is_file(filename: $templateFilePath)) {
            $templateFilePath = $fullContentFilePath;
        }
        $core = Core::get();
        $tplEngine = new TemplateEngine(
            templateCacheInterface: new DirectoryTemplateCache(
                cachePath: $core->cacheDirectory,
                templateBaseDirectory: $core->viewDirectory
            ),
            tplNsPrefix: 'tst'
        );
        $htmlAfterReplacements = $tplEngine->getResultAsHtml(
            tplFile: $templateFilePath,
            dataPool: $this->replacements->getArrayObject()
        );

        return preg_replace_callback(
            pattern: '/(\s+id="nav-(.+?)")(\s+class="(.+?)")?/',
            callback: [$this, 'setCSSActive'],
            subject: $htmlAfterReplacements
        );
    }

    private function setCSSActive(array $m): string
    {
        if (!in_array(needle: $m[2], haystack: $this->activeHtmlIds)) {
            // The id is not within activeHtmlIds, so we just return the whole unmodified string
            return $m[0];
        }

        // The id is within activeHtmlIds, so we need to add the "active" class
        return $m[1] . ' class="' . (isset($m[3]) ? $m[4] . ' ' : '') . 'active"';
    }
}