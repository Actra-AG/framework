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
use actra\yuf\core\InputParameter;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\common\Helper;
use app\libs\db\DbDocumentRepository;
use app\libs\db\DbEventRepository;
use app\libs\table\EventDocumentTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class event extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';
    public const string PARAM_REJECT = 'reject';
    public const string REMOVE_DOCUMENT = 'removeDocument';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
          inputParameter: new InputParameter(
            name: event::PARAM_REMOVE,
            isRequired: false
          )
        );
        $inputParameterCollection->add(
          inputParameter: new InputParameter(
            name: event::PARAM_ADDED,
            isRequired: false
          )
        );
        $inputParameterCollection->add(
          inputParameter: new InputParameter(
            name: event::PARAM_CHANGED,
            isRequired: false
          )
        );
        $inputParameterCollection->add(
          inputParameter: new InputParameter(
            name: event::PARAM_REJECT,
            isRequired: false
          )
        );
        $inputParameterCollection->add(
          inputParameter: new InputParameter(
            name: event::REMOVE_DOCUMENT,
            isRequired: false
          )
        );
        parent::__construct(
          inputParameterCollection: $inputParameterCollection,
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
        return HtmlText::encoded(textContent: 'Details zum Anlass');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbEvent = DbEventRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbEvent === null) {
            throw new NotFoundException();
        }
        if (
          $this->getInputString(keyName: event::PARAM_REMOVE) !== null
          && $dbEvent->userCanEdit()
        ) {
            Helper::deleteEvent(dbEvent: $dbEvent);
            HttpResponse::redirectAndExit(relativeOrAbsoluteUri: events::getPath() . '?' . events::PARAM_REMOVED);
        }
        if (
          $this->getInputString(keyName: event::PARAM_REJECT) !== null
          && $dbEvent->canReject()
        ) {
            DbEventRepository::deny(ID: $dbEvent->ID);
            $dbEvent = DbEventRepository::selectByID(ID: $dbEvent->ID);
        }
        $removeDocument = $this->getInputInteger(keyName: event::REMOVE_DOCUMENT);
        if (
          $removeDocument !== null
          && $dbEvent->userCanEdit()
        ) {
            $dbDocument = DbDocumentRepository::selectByID(ID: $removeDocument);
            if ($dbDocument->eventID === $dbEvent->ID) {
                Helper::deleteDocument(dbDocument: $dbDocument);
            }
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
          identifier: 'modHref',
          content: $dbEvent->userCanEdit() ? eventMod::getPath(ID: $dbEvent->ID) : ''
        );
        $replacements->addEncodedText(
          identifier: 'activateHref',
          content: $dbEvent->canActivate() ? eventActivate::getPath(ID: $dbEvent->ID) : ''
        );
        $replacements->addEncodedText(
          identifier: 'deactivateHref',
          content: $dbEvent->canReject() ? '?' . event::PARAM_REJECT : ''
        );
        $replacements->addEncodedText(
          identifier: 'removeHref',
          content: $dbEvent->userCanEdit() ? '?' . event::PARAM_REMOVE : ''
        );
        $replacements->addBool(
          identifier: 'added',
          booleanValue: $this->getInputString(keyName: event::PARAM_ADDED) !== null
        );
        $replacements->addBool(
          identifier: 'changed',
          booleanValue: $this->getInputString(keyName: event::PARAM_CHANGED) !== null
        );
        $replacements->addBool(
          identifier: 'isAccepted',
          booleanValue: $dbEvent->isAccepted()
        );
        $replacements->addBool(
          identifier: 'isRejected',
          booleanValue: $dbEvent->isRejected()
        );
        $replacements->addDataObject(
          identifier: 'event',
          htmlDataObject: $dbEvent->render()
        );
        $replacements->addEncodedText(
          identifier: 'addDocumentHref',
          content: $dbEvent->userCanEdit() ? documentAdd::getPath(eventID: $dbEvent->ID) : ''
        );
        $replacements->addEncodedText(
          identifier: 'documents',
          content: new EventDocumentTable(
            eventID: $dbEvent->ID,
            userCanEdit: $dbEvent->userCanEdit()
          )->render()
        );
    }

    public static function getPath(int|string $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'event-' . $ID . '.html';
    }
}