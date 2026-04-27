<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\InputParameter;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use actra\yuf\layout\NavigationItem;
use app\libs\common\Helper;
use app\libs\table\PagesTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class pages extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: pages::PARAM_REMOVE,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: pages::PARAM_ADDED,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: pages::PARAM_CHANGED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            activeHtmlIdList: [
                'pages',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'pages',
            href: pages::getPath() . '?reset',
            svgPath: '',
            title: 'Seiteninhalte',
            requiredAccessRights: pages::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'pages.html';
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::EDITOR->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Seiteninhalte');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $remove = $this->getInputString(keyName: pages::PARAM_REMOVE);
        if ($remove !== null) {
            $pageName = base64_decode(string: $remove);
            if (in_array(
                needle: $pageName,
                haystack: Helper::listFrontendPages()
            )) {
                $htmlFilePath = Helper::getFrontendHtmlPath() . '/' . $pageName;
                if (file_exists(filename: $htmlFilePath)) {
                    unlink(filename: $htmlFilePath);
                }
                $phpFilePath = Helper::getFrontendPhpPath() . '/' . str_replace(
                        search: '.html',
                        replace: '.php',
                        subject: $pageName
                    );
                if (file_exists(filename: $phpFilePath)) {
                    unlink(filename: $phpFilePath);
                }
            }
        }
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'addHref',
            content: pageAdd::getPath()
        );
        $replacements->addBool(
            identifier: 'removed',
            booleanValue: $remove !== null
        );
        $replacements->addBool(
            identifier: 'added',
            booleanValue: $this->getInputString(keyName: pages::PARAM_ADDED) !== null
        );
        $replacements->addBool(
            identifier: 'changed',
            booleanValue: $this->getInputString(keyName: pages::PARAM_CHANGED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'table',
            content: new PagesTable()->render()
        );
    }
}