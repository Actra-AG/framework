<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\backend\libs\db\DbAuthGroupRepository;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\HttpResponse;
use actra\yuf\core\InputParameter;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\common\Helper;
use app\libs\db\DbClubRepository;
use app\libs\db\DbMemberRepository;
use app\libs\table\MemberEventTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class member extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_REMOVE,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_ADDED,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: member::PARAM_CHANGED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            maxAllowedPathVars: 1,
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
        return HtmlText::encoded(textContent: 'Mitglied');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbMember = DbMemberRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbMember === null) {
            throw new NotFoundException();
        }
        $dbAuthUser = $dbMember->dbAuthUser;
        if ($this->getInputString(keyName: member::PARAM_REMOVE) !== null) {
            Helper::deleteMember(ID: $dbAuthUser->ID);
            HttpResponse::redirectAndExit(relativeOrAbsoluteUri: members::getPath() . '?' . members::PARAM_REMOVED);
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'removeHref',
            content: '?' . member::PARAM_REMOVE
        );
        $replacements->addBool(
            identifier: 'isPendingRequest',
            booleanValue: $dbMember->isPendingRequest()
        );
        if ($dbMember->isPendingRequest()) {
            $replacements->addEncodedText(
                identifier: 'acceptHref',
                content: accept::getPath(ID: $dbAuthUser->ID)
            );
            $replacements->addEncodedText(
                identifier: 'denyHref',
                content: deny::getPath(ID: $dbAuthUser->ID)
            );
        }
        $replacements->addBool(
            identifier: 'added',
            booleanValue: $this->getInputString(keyName: member::PARAM_ADDED) !== null
        );
        $replacements->addBool(
            identifier: 'changed',
            booleanValue: $this->getInputString(keyName: member::PARAM_CHANGED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'memberModHref',
            content: memberMod::getPath(ID: $dbAuthUser->ID)
        );
        $replacements->addDataObject(
            identifier: 'member',
            htmlDataObject: $dbMember->render()
        );
        $replacements->addEncodedText(
            identifier: 'registered',
            content: $dbAuthUser->registered->format(format: 'd.m.Y H:i:s')
        );
        $replacements->addEncodedText(
            identifier: 'invitedDate',
            content: $dbAuthUser->isInvited() ? $dbAuthUser->invitedDate->format(format: 'd.m.Y H:i:s') : ''
        );
        $replacements->addEncodedText(
            identifier: 'lastLogin',
            content: $dbAuthUser->renderLastLogin()
        );
        $replacements->addHtmlDataObjectCollection(
            identifier: 'userGroups',
            htmlDataObjectCollection: DbAuthGroupRepository::listByUserID(userID: $dbAuthUser->ID)->render()
        );
        $replacements->addEncodedText(
            identifier: 'active',
            content: $dbAuthUser->isActive ? 'ja' : 'nein'
        );
        $replacements->addHtmlDataObjectCollection(
            identifier: 'clubAccess',
            htmlDataObjectCollection: DbClubRepository::listMemberClubs(memberID: $dbAuthUser->ID)->render()
        );
        $replacements->addEncodedText(
            identifier: 'events',
            content: new MemberEventTable(memberID: $dbAuthUser->ID)->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'member-' . $ID . '.html';
    }
}