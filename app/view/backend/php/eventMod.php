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
use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\db\DbEventRepository;
use app\libs\form\EventModForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class eventMod extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            maxAllowedPathVars: 1,
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
        return HtmlText::encoded(textContent: 'Anlass bearbeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbEvent = DbEventRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if (
            $dbEvent === null
            || !$dbEvent->userCanEdit()
        ) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $eventModForm = new EventModForm(dbEvent: $dbEvent);
        if ($eventModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: event::getPath(ID: $dbEvent->ID) . '?' . event::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $eventModForm->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'eventMod-' . $ID . '.html';
    }
}