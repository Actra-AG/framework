<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\TextAreaField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\common\Helper;
use app\libs\db\DbPageRepository;
use app\view\backend\php\pages;

class PageAddForm extends Form
{
    private readonly TextField $pageNameField;
    private readonly TextAreaField $htmlField;
    private readonly TextAreaField $phpField;

    public function __construct()
    {
        parent::__construct(name: 'pageAddForm');
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->pageNameField = new TextField(
                name: 'pageNameField',
                label: HtmlText::encoded(textContent: 'Dateiname'),
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie einen Dateinamen ein.'),
                placeholder: 'dateiname.html'
            )
        );
        $this->addField(
            formField: $this->htmlField = new TextAreaField(
                name: 'htmlField',
                label: HtmlText::encoded(textContent: 'HTML'),
                value: null,
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den HTML-Inhalt ein.'),
            )
        );
        $this->addField(
            formField: $this->phpField = new TextAreaField(
                name: 'phpField',
                label: HtmlText::encoded(textContent: 'PHP'),
                value: null
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
        $pageName = $this->pageNameField->getRawValue();
        if (preg_match(pattern: '/^[a-zA-Z0-9_]+\.html$/', subject: $pageName) !== 1) {
            $this->pageNameField->addError(
                errorMessage: 'Der Dateiname darf nur aus Buchstaben, Zahlen und Unterstrichen bestehen und muss mit <strong>.html</strong> enden.',
                isEncodedForRendering: true
            );
            return false;
        }
        $htmlContent = $this->htmlField->getRawValue();
        $phpContent = $this->phpField->getRawValue();
        DbPageRepository::insert(
            htmlFileName: $pageName,
            html: $htmlContent,
            php: $phpContent
        );
        file_put_contents(
            filename: Helper::getFrontendHtmlPath() . '/' . $pageName,
            data: $htmlContent
        );
        if ($phpContent !== '') {
            file_put_contents(
                filename: Helper::getFrontendPhpPath() . '/' . str_replace(
                    search: '.html',
                    replace: '.php',
                    subject: $pageName
                ),
                data: $phpContent
            );
        }

        return true;
    }
}