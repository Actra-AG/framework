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
use app\libs\form\NewsAddForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class newsAdd extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'news',
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
        return HtmlText::encoded(textContent: 'Neuigkeit hinzufügen');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $replacements = $htmlDocument->replacements;
        $newsAddForm = new NewsAddForm();
        if ($newsAddForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: news::getPath() . '?' . news::PARAM_ADDED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $newsAddForm->render()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'newsAdd.html';
    }
}