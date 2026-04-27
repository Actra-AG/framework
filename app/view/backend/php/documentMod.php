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
use app\libs\db\DbDocumentRepository;
use app\libs\db\DbEventRepository;
use app\libs\form\DocumentModForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class documentMod extends BackendView
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
        return HtmlText::encoded(textContent: 'Dokument bearbeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbDocument = DbDocumentRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbDocument === null) {
            throw new NotFoundException();
        }
        $dbEvent = DbEventRepository::selectByID(ID: $dbDocument->eventID);
        if (!$dbEvent->userCanEdit()) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $documentModForm = new DocumentModForm(dbDocument: $dbDocument);
        if ($documentModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: event::getPath(ID: $dbDocument->eventID) . '?' . event::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $documentModForm->render()
        );
    }

    public static function getPath(int|string $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'documentMod-' . $ID . '.html';
    }
}