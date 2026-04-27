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
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use actra\yuf\layout\NavigationItem;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class webmail extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'webmail',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'webmail',
            href: webmail::getPath() . '?reset',
            svgPath: '',
            title: 'Webmail',
            requiredAccessRights: webmail::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'webmail.html';
    }

    public static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::BACKEND_ACCESS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Webmail');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
    }
}