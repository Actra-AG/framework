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
use app\libs\db\DbClubRepository;
use app\libs\form\ClubSearchForm;
use app\libs\table\ClubTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class clubs extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: clubs::PARAM_REMOVE,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: clubs::PARAM_ADDED,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: clubs::PARAM_CHANGED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            activeHtmlIdList: [
                'clubs',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'clubs',
            href: clubs::getPath() . '?reset',
            svgPath: '',
            title: 'Vereine',
            requiredAccessRights: clubs::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'clubs.html';
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::MANAGE_USERS->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Vereine');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $remove = $this->getInputInteger(keyName: news::PARAM_REMOVE);
        if ($remove !== null) {
            DbClubRepository::delete(ID: $remove);
        }
        $clubSearchForm = new ClubSearchForm();

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'addHref',
            content: clubAdd::getPath()
        );
        $replacements->addBool(
            identifier: 'removed',
            booleanValue: $remove !== null
        );
        $replacements->addBool(
            identifier: 'added',
            booleanValue: $this->getInputString(keyName: clubs::PARAM_ADDED) !== null
        );
        $replacements->addBool(
            identifier: 'changed',
            booleanValue: $this->getInputString(keyName: clubs::PARAM_CHANGED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'searchForm',
            content: $clubSearchForm->render()
        );
        $replacements->addEncodedText(
            identifier: 'table',
            content: new ClubTable(clubSearchForm: $clubSearchForm)->render()
        );
    }
}