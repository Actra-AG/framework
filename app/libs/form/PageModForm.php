<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\common\Helper;
use app\libs\db\DbPageRepository;
use app\view\backend\php\pages;

class PageModForm extends Form
{
    private readonly TextAreaField $htmlField;
    private readonly TextAreaField $phpField;
    private readonly string $htmlFilePath;
    private readonly string $phpFilePath;

    public function __construct(private readonly string $pageName)
    {
        parent::__construct(name: 'PageModForm');
        $this->addCssClass(className: 'form');
        $this->htmlFilePath = Helper::getFrontendHtmlPath() . '/' . $pageName;
        $this->addField(
            formField: $this->htmlField = new TextAreaField(
                name: 'htmlField',
                label: HtmlText::encoded(textContent: 'HTML'),
                value: file_get_contents(
                    filename: $this->htmlFilePath
                ),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den HTML-Inhalt ein.'),
            )
        );
        $this->phpFilePath = Helper::getFrontendPhpPath() . '/' . str_replace(
                search: '.html',
                replace: '.php',
                subject: $pageName
            );
        $this->addField(
            formField: $this->phpField = new TextAreaField(
                name: 'phpField',
                label: HtmlText::encoded(textContent: 'PHP'),
                value: file_exists(filename: $this->phpFilePath) ? file_get_contents(
                    filename: $this->phpFilePath
                ) : null
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: pages::getPath()
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $htmlContent = $this->htmlField->getRawValue();
        $phpContent = $this->phpField->getRawValue();
        DbPageRepository::insert(
            htmlFileName: $this->pageName,
            html: $htmlContent,
            php: $phpContent
        );
        file_put_contents(
            filename: $this->htmlFilePath,
            data: $htmlContent
        );
        if ($phpContent !== '') {
            file_put_contents(
                filename: $this->phpFilePath,
                data: $phpContent
            );
        }

        return true;
    }
}