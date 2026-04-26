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
use app\libs\form\MemberSearchForm;
use app\libs\table\MemberTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class members extends BackendView
{
    public const string PARAM_REMOVED = 'removed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: members::PARAM_REMOVED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            activeHtmlIdList: [
                'members',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'members',
            href: members::getPath() . '?reset',
            svgPath: '',
            title: 'Mitglieder',
            requiredAccessRights: members::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'members.html';
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::MANAGE_USERS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Mitglieder');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $memberSearchForm = new MemberSearchForm();
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'addHref',
            content: memberAdd::getPath()
        );
        $replacements->addBool(
            identifier: 'removed',
            booleanValue: $this->getInputString(keyName: members::PARAM_REMOVED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'searchForm',
            content: $memberSearchForm->render()
        );
        $replacements->addEncodedText(
            identifier: 'table',
            content: new MemberTable(memberSearchForm: $memberSearchForm)->render()
        );
    }
}