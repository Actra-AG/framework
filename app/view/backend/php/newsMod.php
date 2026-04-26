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
use app\libs\db\DbNewsRepository;
use app\libs\form\NewsModForm;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class newsMod extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            maxAllowedPathVars: 1,
            activeHtmlIdList: [
                'news',
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
        return HtmlText::encoded(textContent: 'Neuigkeit bearbeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbNews = DbNewsRepository::selectByID(ID: (int)$this->getPathVar(nr: 1));
        if ($dbNews === null) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $newsModForm = new NewsModForm(dbNews: $dbNews);
        if ($newsModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: news::getPath() . '?' . news::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'form',
            content: $newsModForm->render()
        );
    }

    public static function getPath(string|int $ID): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'newsMod-' . $ID . '.html';
    }
}