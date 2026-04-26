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
use app\libs\backend\AuthUserHelper;
use app\libs\db\DbEventRepository;
use app\libs\db\DbMemberRepository;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class overview extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            activeHtmlIdList: [
                'overview',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'overview',
            href: overview::getPath() . '?reset',
            svgPath: '',
            title: 'Übersicht',
            requiredAccessRights: overview::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'overview.html';
    }

    public static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::BACKEND_ACCESS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Übersicht');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $replacements = $htmlDocument->replacements;
        $replacements->addBool(
            identifier: 'isBoard',
            booleanValue: AuthUserHelper::isBoard()
        );
        $isAdmin = AuthUserHelper::isAdmin();
        $replacements->addBool(
            identifier: 'isAdmin',
            booleanValue: $isAdmin
        );
        if ($isAdmin) {
            $replacements->addHtmlDataObjectCollection(
                identifier: 'pendingRegistrationRequests',
                htmlDataObjectCollection: DbMemberRepository::listPendingRegistrationRequests()->render()
            );
            $replacements->addHtmlDataObjectCollection(
                identifier: 'eventsToCheck',
                htmlDataObjectCollection: DbEventRepository::listToCheck()->render()
            );
        }
    }
}