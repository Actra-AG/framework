<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\view;

use actra\yuf\auth\AccessRightCollection;
use actra\yuf\core\BaseView;
use actra\yuf\core\InputParameterCollection;
use actra\yuf\html\HtmlDocument;
use FilesystemIterator;
use site\settings\Navigation;

abstract class FrontendView extends BaseView
{
    public function __construct()
    {
        parent::__construct(
            requiredViewGroupName: 'frontend',
            ipWhitelist: [],
            authUser: null,
            requiredAccessRights: AccessRightCollection::createEmpty(),
            inputParameterCollection: new InputParameterCollection()
        );
    }

    public function execute(): void
    {
        $htmlDocument = HtmlDocument::get();
        $this->prepareHtmlDocument(htmlDocument: $htmlDocument);
        $replacements = $htmlDocument->replacements;
        $replacements->addEncodedText(
            identifier: 'pageTitle',
            content: $this->getPageTitle()
        );
        $replacements->addEncodedText(
            identifier: 'randomHeaderImage',
            content: $this->selectRandomHeaderImage()
        );
        $activeNavigationItems = $this->getActiveNavigationItems();
        $replacements->addHtmlDataObjectCollection(
            identifier: 'navigation',
            htmlDataObjectCollection: Navigation::render(
                activeNavigationItems: $activeNavigationItems,
                level: 1
            )
        );
        foreach ($activeNavigationItems as $key => $activeNavigationItem) {
            $htmlDocument->setActiveHtmlId(
                key: $key,
                val: $activeNavigationItem
            );
        }
    }

    abstract protected function getPageTitle(): string;

    abstract protected function getActiveNavigationItems(): array;

    abstract protected function prepareHtmlDocument(HtmlDocument $htmlDocument): void;

    private function selectRandomHeaderImage(): string
    {
        $relativeDir = '/images/head/';
        $files = iterator_to_array(
            iterator: new FilesystemIterator(
                directory: $_SERVER['DOCUMENT_ROOT'] . $relativeDir
            )
        );
        $randomFile = array_rand(array: $files);
        return $relativeDir . $files[$randomFile]->getFilename();
    }
}