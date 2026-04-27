<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\form;

use actra\yuf\form\component\collection\Form;
use actra\yuf\form\component\field\FileField;
use actra\yuf\form\component\field\TextField;
use actra\yuf\form\component\FormControl;
use actra\yuf\html\HtmlText;
use app\libs\common\Helper;
use app\libs\db\DbDocumentRepository;
use app\libs\db\DbEvent;
use app\libs\db\DbFileFormatRepository;
use app\view\backend\php\event;

class DocumentAddForm extends Form
{
    private readonly FileField $fileField;
    private readonly TextField $titleField;

    public function __construct(private readonly DbEvent $dbEvent)
    {
        parent::__construct(
            name: 'DocumentAddForm',
            acceptUpload: true
        );
        $this->addCssClass(className: 'form');
        $this->addField(
            formField: $this->fileField = new FileField(
                name: 'fileField',
                label: HtmlText::encoded(textContent: 'Datei'),
                requiredError: HtmlText::encoded(textContent: 'Bitte wählen Sie eine Datei aus.')
            )
        );
        $this->addField(
            formField: $this->titleField = new TextField(
                name: 'titleField',
                label: HtmlText::encoded(textContent: 'Titel'),
                value: '',
                requiredError: HtmlText::encoded(textContent: 'Bitte geben Sie den Titel ein.')
            )
        );
        $this->addComponent(
            formComponent: new FormControl(
                name: 'save',
                submitLabel: HtmlText::encoded(textContent: 'Speichern'),
                cancelLink: event::getPath(ID: $dbEvent->ID)
            )
        );
    }

    public function process(): bool
    {
        if (!parent::validate()) {
            return false;
        }
        $fileField = $this->fileField;
        $fileDataModel = current(array: $fileField->getFiles());
        $extension = DbFileFormatRepository::getExtensionByMimeType(mimeType: $fileDataModel->type);
        if ($extension === null) {
            $fileField->addError(
                errorMessage: 'Der Dateityp ' . $fileDataModel->type . ' ist nicht erlaubt.',
                isEncodedForRendering: true
            );
            return false;
        }
        if (
            preg_match(
                pattern: '/^([a-zA-Z0-9\-_]+)\.([a-zA-Z0-9]{2,3})$/',
                subject: $fileDataModel->name
            ) !== 1
        ) {
            $fileField->addError(
                errorMessage: 'Ungültiger Dateiname. Nur Kleinbuchstaben, Zahlen, Bindestriche und Unterstriche sind erlaubt.',
                isEncodedForRendering: true
            );
            return false;
        }
        $documentID = DbDocumentRepository::insert(
            eventID: $this->dbEvent->ID,
            title: $this->titleField->getRawValue(),
            fileName: $fileDataModel->name,
            mimeType: $fileDataModel->type,
        );
        Helper::saveDocument(
            fileDataModel: $fileDataModel,
            ID: $documentID,
            extension: $extension
        );
        return true;
    }
}