<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\HttpResponse;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\form\ClubAddForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class clubAdd extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'clubs',
            ],
            useNavigator: true
        );
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::EDITOR->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Verein hinzufügen');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $replacements = $htmlDocument->replacements;
        $clubAddForm = new ClubAddForm();
        if ($clubAddForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: clubs::getPath() . '?' . clubs::PARAM_ADDED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $clubAddForm->render()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'clubAdd.html';
    }
}