<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\view\backend\php;

use actra\backend\BackendView;
use actra\backend\libs\auth\MyAuthUser;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\InputParameter;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\html\HtmlDocument;
use actra\yuf\html\HtmlText;
use actra\yuf\layout\NavigationItem;
use app\libs\form\EventSearchForm;
use app\libs\table\EventTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class events extends BackendView
{
    public const string PARAM_REMOVED = 'removed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: events::PARAM_REMOVED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            activeHtmlIdList: [
                'events',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'events',
            href: events::getPath() . '?reset',
            svgPath: '',
            title: 'Jahresprogramm',
            requiredAccessRights: events::getRequiredAccessRights()
        );
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::BACKEND_ACCESS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Jahresprogramm');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $eventSearchForm = new EventSearchForm(memberID: MyAuthUser::get()->ID);
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'addHref',
            content: eventAdd::getPath()
        );
        $replacements->addBool(
            identifier: 'removed',
            booleanValue: $this->getInputString(keyName: events::PARAM_REMOVED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'searchForm',
            content: $eventSearchForm->render()
        );
        $replacements->addEncodedText(
            identifier: 'table',
            content: new EventTable(eventSearchForm: $eventSearchForm)->render()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'events.html';
    }
}