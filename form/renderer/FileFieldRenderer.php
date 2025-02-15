<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\form\renderer;

use framework\form\component\field\FileField;
use framework\form\FormRenderer;
use framework\html\HtmlEncoder;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;
use framework\html\HtmlText;

class FileFieldRenderer extends FormRenderer
{
    public bool $enhanceMultipleField = true;

    public function __construct(private readonly FileField $fileField)
    {
    }

    public function prepare(): void
    {
        $fileField = $this->fileField;

        $alreadyUploadedFiles = $fileField->getFiles();

        $stillAllowedToUploadCount = $fileField->maxFileUploadCount - count($alreadyUploadedFiles);
        if ($stillAllowedToUploadCount < 0) {
            $stillAllowedToUploadCount = 0;
        }

        $wrapperClass = ($stillAllowedToUploadCount > 1 && $this->enhanceMultipleField) ? 'fileupload-enhanced' : 'fileupload';
        $wrapper = new HtmlTag('div', false, [
            new HtmlTagAttribute('class', $wrapperClass, true),
            new HtmlTagAttribute('data-max-files', $stillAllowedToUploadCount, true),
        ]);

        if (!empty($alreadyUploadedFiles)) {
            $fileListBox = new HtmlTag('ul', false, [
                new HtmlTagAttribute('class', 'list-fileupload', true),
            ]);
            $htmlContent = '';
            foreach ($alreadyUploadedFiles as $hash => $fileDataModel) {
                $htmlContent .= '<li><b>' . HtmlEncoder::encode(
                        value: $fileDataModel->name
                    ) . '</b> <button type="submit" name="' . $this->fileField->name . '_removeAttachment" value="' . HtmlEncoder::encode(
                        value: $hash
                    ) . '">löschen</button>';
            }
            $fileListBox->addText(HtmlText::encoded($htmlContent));
            $wrapper->addTag($fileListBox);
        }

        $inputTag = new HtmlTag('input', true);
        $inputTag->addHtmlTagAttribute(new HtmlTagAttribute('type', 'file', true));
        $inputTag->addHtmlTagAttribute(new HtmlTagAttribute('name', $fileField->name . '[]', true));
        $inputTag->addHtmlTagAttribute(new HtmlTagAttribute('id', $fileField->id, true));
        if ($stillAllowedToUploadCount > 1) {
            $inputTag->addHtmlTagAttribute(new HtmlTagAttribute('multiple', null, true));
        }
        FormRenderer::addAriaAttributesToHtmlTag($fileField, $inputTag);
        $wrapper->addTag($inputTag);

        // Add the fileStore-Pointer-ID for the SESSION as hidden field
        $wrapper->addTag(new HtmlTag('input', true, [
            new HtmlTagAttribute('type', 'hidden', true),
            new HtmlTagAttribute('name', $this->fileField->name . '_UID', true),
            new HtmlTagAttribute('value', $fileField->uniqueSessFileStorePointer, true),
        ]));

        $this->setHtmlTag($wrapper);
    }
}