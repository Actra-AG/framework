<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\backend\libs\auth\MyAuthUser;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\HttpResponse;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\form\EventAddForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class eventAdd extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'events',
            ],
            useNavigator: true
        );
    }

    public static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::BACKEND_ACCESS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Event hinzufügen');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $replacements = $htmlDocument->replacements;
        $eventAddForm = new EventAddForm(myAuthUser: MyAuthUser::get());
        if ($eventAddForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: event::getPath(ID: $eventAddForm->eventID) . '?' . event::PARAM_ADDED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $eventAddForm->render()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'eventAdd.html';
    }
}