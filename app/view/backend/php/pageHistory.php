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
use app\libs\common\Helper;
use app\libs\form\PageModForm;
use app\libs\table\PageHistoryTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class pageHistory extends BackendView
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
        return HtmlText::encoded(textContent: 'Seitenarchiv');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $pageName = base64_decode(string: (string)$this->getPathVar(nr: 1));
        if (
            str_contains(
                haystack: $pageName,
                needle: '/'
            )
            || !in_array(
                needle: $pageName,
                haystack: Helper::listFrontendPages()
            )
        ) {
            throw new NotFoundException();
        }
        $replacements = $htmlDocument->replacements;
        $pageModForm = new PageModForm(pageName: $pageName);
        if ($pageModForm->process()) {
            HttpResponse::redirectAndExit(
                relativeOrAbsoluteUri: pages::getPath() . '?' . pages::PARAM_CHANGED
            );
        }
        $replacements->addEncodedText(
            identifier: 'table',
            content: new PageHistoryTable(pageName: $pageName)->render()
        );
    }

    public static function getPath(string $fileName): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'pageHistory-' . $fileName . '.html';
    }
}