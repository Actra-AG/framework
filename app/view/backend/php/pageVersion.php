<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\exception\NotFoundException;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use app\libs\db\DbPageRepository;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class pageVersion extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            maxAllowedPathVars: 1,
            activeHtmlIdList: [
                'pages',
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
        return HtmlText::encoded(textContent: 'Seitenversion');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbPage = DbPageRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbPage === null) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addDataObject(
            identifier: 'page',
            htmlDataObject: $dbPage->render()
        );
    }

    public static function getPath(int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'pageVersion-' . $ID . '.html';
    }
}