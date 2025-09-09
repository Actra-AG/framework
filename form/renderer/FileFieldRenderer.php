<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
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
        $stillAllowedToUploadCount = $fileField->maxFileUploadCount - count(value: $alreadyUploadedFiles);
        if ($stillAllowedToUploadCount < 0) {
            $stillAllowedToUploadCount = 0;
        }
        $wrapperClass = ($stillAllowedToUploadCount > 1 && $this->enhanceMultipleField) ? 'fileupload-enhanced' : 'fileupload';
        $divFileUpload = new HtmlTag(
            name: 'div',
            selfClosing: false
        );
        $divFileUpload->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'class',
                value: $wrapperClass,
                valueIsEncodedForRendering: true
            )
        );
        $divFileUpload->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'data-max-files',
                value: $stillAllowedToUploadCount,
                valueIsEncodedForRendering: true
            )
        );
        if (count(value: $alreadyUploadedFiles) > 0) {
            $ulFileUploadList = new HtmlTag(
                name: 'ul',
                selfClosing: false
            );
            $ulFileUploadList->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'class',
                    value: 'fileupload-list',
                    valueIsEncodedForRendering: true
                )
            );
            $htmlContent = '';
            foreach ($alreadyUploadedFiles as $hash => $fileDataModel) {
                $htmlContent .= '<li><span>' . HtmlEncoder::encode(
                        value: $fileDataModel->name
                    ) . '</span> <button type="submit" name="' . $this->fileField->name . '_removeAttachment" value="' . HtmlEncoder::encode(
                        value: $hash
                    ) . '">löschen</button></li>';
            }
            $ulFileUploadList->addText(htmlText: HtmlText::encoded(textContent: $htmlContent));
            $divFileUpload->addTag(htmlTag: $ulFileUploadList);
        }
        $inputTag = new HtmlTag(
            name: 'input',
            selfClosing: true
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'type',
                value: 'file',
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'name',
                value: $fileField->name . '[]',
                valueIsEncodedForRendering: true
            )
        );
        $inputTag->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'id',
                value: $fileField->id,
                valueIsEncodedForRendering: true
            )
        );
        if ($stillAllowedToUploadCount > 1) {
            $inputTag->addHtmlTagAttribute(
                htmlTagAttribute: new HtmlTagAttribute(
                    name: 'multiple',
                    value: null,
                    valueIsEncodedForRendering: true
                )
            );
        }
        FormRenderer::addAriaAttributesToHtmlTag(
            formField: $fileField,
            parentHtmlTag: $inputTag
        );
        $divFileUpload->addTag(htmlTag: $inputTag);
        // Add the fileStore-Pointer-ID for the SESSION as a hidden field
        $hiddenField = new HtmlTag(
            name: 'input',
            selfClosing: true
        );
        $hiddenField->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'type',
                value: 'hidden',
                valueIsEncodedForRendering: true
            )
        );
        $hiddenField->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'name',
                value: $this->fileField->name . '_UID',
                valueIsEncodedForRendering: true
            )
        );
        $hiddenField->addHtmlTagAttribute(
            htmlTagAttribute: new HtmlTagAttribute(
                name: 'value',
                value: $fileField->uniqueSessFileStorePointer,
                valueIsEncodedForRendering: true
            )
        );
        $divFileUpload->addTag(htmlTag: $hiddenField);
        $this->setHtmlTag(htmlTag: $divFileUpload);
    }
}