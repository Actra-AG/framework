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
use app\libs\table\BoardEventTable;
use app\settings\AuthRightEnum;
use app\settings\ProjectSettings;

class board extends BackendView
{
    public function __construct()
    {
        parent::__construct(
            inputParameterCollection: new InputParameterCollection(),
            maxAllowedPathVars: 1,
            activeHtmlIdList: [
                'board',
            ],
            useNavigator: true
        );
    }

    public static function getNavigationItem(): NavigationItem
    {
        return new NavigationItem(
            navKey: 'board',
            href: board::getPath() . '?reset',
            svgPath: '',
            title: 'Vorstand',
            requiredAccessRights: board::getRequiredAccessRights()
        );
    }

    public static function getPath(?int $year = null): string
    {
        return ProjectSettings::BACKEND_DIRECTORY . 'board' . ($year === null ? '' : '-' . $year) . '.html';
    }

    public static function getRequiredAccessRights(): AccessRightCollection
    {
        return AccessRightCollection::createFromStringArray(input: [
            AuthRightEnum::BOARD_MEMBER->value,
        ]);
    }

    protected function getPageTitle(): HtmlText
    {
        return HtmlText::encoded(textContent: 'Vorstand');
    }

    protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void
    {
        $dbEventCollection = DbEventRepository::select(dbQuery: DbEventRepository::getBoardEventQuery());
        $inputYear = (int)$this->getPathVar(nr: 1);
        $selectedYear = (
            $inputYear < $dbEventCollection->getMinYear()
            || $inputYear > $dbEventCollection->getMaxYear()
        ) ? (int)date(format: 'Y') : $inputYear;
        $replacements = $htmlDocument->replacements;
        $replacements->addBool(
            identifier: 'isBoard',
            booleanValue: AuthUserHelper::isBoard()
        );
        $replacements->addEncodedText(
            identifier: 'yearNavigation',
            content: $dbEventCollection->renderYearNavigation(
                selectedYear: $selectedYear,
                path: board::getPath(year: 0)
            )
        );
        $replacements->addEncodedText(
            identifier: 'list',
            content: new BoardEventTable(selectedYear: $selectedYear)->render()
        );
    }
}