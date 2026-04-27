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
use app\libs\db\DbClubRepository;
use app\libs\form\ClubModForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class clubMod extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            maxAllowedPathVars: 1,
            activeHtmlIdList: [
                'clubs',
            ],
            useNavigator: true
        );
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::EDITOR->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Verein bearbeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbClub = DbClubRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbClub === null) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $clubModForm = new ClubModForm(dbClub: $dbClub);
        if ($clubModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: clubs::getPath() . '?' . clubs::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $clubModForm->render()
        );
    }

    public static function getPath(string|int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'clubMod-' . $ID . '.html';
    }
}