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
use app\libs\form\DocumentAddForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class documentAdd extends BackendView
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
            AuthRightEnum::BACKEND_ACCESS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Dokument hochladen');
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
        $documentAddForm = new DocumentAddForm(dbEvent: $dbEvent);
        if ($documentAddForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: event::getPath(ID: $dbEvent->ID) . '?' . event::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $documentAddForm->render()
        );
    }

    public static function getPath(int|string $eventID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'documentAdd-' . $eventID . '.html';
    }
}