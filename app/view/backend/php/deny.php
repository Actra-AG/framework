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
use app\libs\db\DbMemberRepository;
use app\libs\form\MemberRejectForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class deny extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
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
        return HtmlText::encoded(textContent: 'Registrierungsantrag ablehnen');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbMember = DbMemberRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if (is_null(value: $dbMember)) {
            throw new NotFoundException();
        }
        $memberRejectForm = new MemberRejectForm(dbAuthUser: $dbMember->dbAuthUser);
        if ($memberRejectForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: member::getPath(
                    ID: $dbMember->dbAuthUser->ID
                )
            );
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'recipient',
            content: $dbMember->dbAuthUser->email
        );
        $replacements->addEncodedText(
            identifier: 'form',
            content: $memberRejectForm->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'deny-' . $ID . '.html';
    }
}