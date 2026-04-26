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
use app\libs\db\DbNewsRepository;
use app\libs\form\NewsSearchForm;
use app\libs\table\NewsTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class news extends BackendView
{
    public const string PARAM_REMOVE = 'remove';
    public const string PARAM_ADDED = 'added';
    public const string PARAM_CHANGED = 'changed';

    public function __construct()
    {
        $inputParameterCollection = new InputParameterCollection();
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: news::PARAM_REMOVE,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: news::PARAM_ADDED,
                isRequired: false
            )
        );
        $inputParameterCollection->add(
            inputParameter: new InputParameter(
                name: news::PARAM_CHANGED,
                isRequired: false
            )
        );
        parent::__construct(
            inputParameterCollection: $inputParameterCollection,
            activeHtmlIdList: [
                'news',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'news',
            href: news::getPath() . '?reset',
            svgPath: '',
            title: 'Neuigkeiten',
            requiredAccessRights: news::getRequiredAccessRights()
        );
    }

    public static function getPath(): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'news.html';
    }

    protected static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::EDITOR->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Neuigkeiten');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $remove = $this->getInputInteger(keyName: news::PARAM_REMOVE);
        if ($remove !== null) {
            DbNewsRepository::delete(ID: $remove);
        }
        $newsSearchForm = new NewsSearchForm();

        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'addHref',
            content: newsAdd::getPath()
        );
        $replacements->addBool(
            identifier: 'removed',
            booleanValue: $remove !== null
        );
        $replacements->addBool(
            identifier: 'added',
            booleanValue: $this->getInputString(keyName: news::PARAM_ADDED) !== null
        );
        $replacements->addBool(
            identifier: 'changed',
            booleanValue: $this->getInputString(keyName: news::PARAM_CHANGED) !== null
        );
        $replacements->addEncodedText(
            identifier: 'searchForm',
            content: $newsSearchForm->render()
        );
        $replacements->addEncodedText(
            identifier: 'table',
            content: new NewsTable(
                newsSearchForm: $newsSearchForm,
                type: 1
            )->render()
        );
    }
}