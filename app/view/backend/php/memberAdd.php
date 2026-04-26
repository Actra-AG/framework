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
use app\libs\form\MemberAddForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class memberAdd extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'members',
            ],
            useNavigator: true
        );
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::MANAGE_USERS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Mitglied hinzufügen');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $replacements = $htmlDocument->replacements;
        $memberAddForm = new MemberAddForm();
        if ($memberAddForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: member::getPath(
                    ID: $memberAddForm->userID
                ) . '?' . member::PARAM_ADDED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $memberAddForm->render()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'memberAdd.html';
    }
}