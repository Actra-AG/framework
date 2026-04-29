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
use app\libs\form\EventActivateForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class eventActivate extends BackendView
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

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::MANAGE_USERS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Anlass publizieren');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbEvent = DbEventRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbEvent === null) {
            throw new NotFoundException();
        }
        $eventActivateForm = new EventActivateForm(dbEvent: $dbEvent);
        if ($eventActivateForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: event::getPath(
                    ID: $dbEvent->ID
                )
            );
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'recipient',
            content: $dbEvent->registeredByEmail
        );
        $replacements->addEncodedText(
            identifier: 'form',
            content: $eventActivateForm->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'eventActivate-' . $ID . '.html';
    }
}