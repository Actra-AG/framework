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
use app\libs\form\MemberModForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class memberMod extends BackendView
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
        return HtmlText::encoded(textContent: 'Mitglied bearbeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbMember = DbMemberRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbMember === null) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $memberModForm = new MemberModForm(dbMember: $dbMember);
        if ($memberModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: member::getPath(
                    ID: $dbMember->dbAuthUser->ID
                ) . '?' . member::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $memberModForm->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'memberMod-' . $ID . '.html';
    }
}