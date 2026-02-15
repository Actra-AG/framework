<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\view;

use FilesystemIterator;
use framework\auth\AccessRightCollection;
use framework\core\BaseView;
use framework\core\InputParameterCollection;
use framework\html\HtmlDataObject;
use framework\html\HtmlDataObjectCollection;
use framework\html\HtmlDocument;

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
        $replacements->addHtmlDataObjectCollection(
            identifier: 'navigation',
            htmlDataObjectCollection: $this->navigation()
        );
        foreach ($this->getActiveNavigationItems() as $key => $activeNavigationItem) {
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

    private function navigation(): HtmlDataObjectCollection
    {
        $navigation = new HtmlDataObjectCollection();
        foreach ([
                     [
                         'id' => 'start',
                         'href' => 'start.html',
                         'label' => 'Startseite',
                         'subNavigation' => [
                             [
                                 'id' => 'newsarchiv',
                                 'href' => 'newsArchiv.html',
                                 'label' => 'Newsarchiv',
                             ],
                         ],
                     ],
                     [
                         'id' => 'vorstand',
                         'href' => 'vorstand.html',
                         'label' => 'Vorstand',
                         'subNavigation' => [
                             [
                                 'id' => 'ehrenmitglieder',
                                 'href' => 'ehrenmitglieder.html',
                                 'label' => 'Ehrenmitglieder',
                             ],
                         ],
                     ],
                     [
                         'id' => 'vereine',
                         'href' => 'vereine300.html',
                         'label' => 'Vereine BSVB',
                         'subNavigation' => [
                             [
                                 'id' => 'vereine300',
                                 'href' => 'vereine300.html',
                                 'label' => '300m Sektionen',
                             ],
                             [
                                 'id' => 'vereine25',
                                 'href' => 'vereine25.html',
                                 'label' => '25/50m Sektionen',
                             ],
                             [
                                 'id' => 'vereinejs',
                                 'href' => 'vereinejs.html',
                                 'label' => 'Jungschützenkurse',
                             ],
                             [
                                 'id' => 'vereinedo',
                                 'href' => 'vereinedo.html',
                                 'label' => 'Div. Organisationen',
                             ],
                         ],
                     ],
                     [
                         'id' => 'jahresprogramm',
                         'href' => 'jpAll.html',
                         'label' => 'Jahresprogramm',
                         'subNavigation' => [
                             [
                                 'id' => 'jpAll',
                                 'href' => 'jpAll.html',
                                 'label' => 'Alle Anlässe',
                             ],
                             [
                                 'id' => 'jpsa',
                                 'href' => 'jp-sa.html',
                                 'label' => 'Schiessanlässe',
                             ],
                             [
                                 'id' => 'jpjs',
                                 'href' => 'jp-js.html',
                                 'label' => 'Jungschützen/Nachwuchs',
                             ],
                             [
                                 'id' => 'jpmw',
                                 'href' => 'jp-mw.html',
                                 'label' => 'Matchwesen',
                             ],
                             [
                                 'id' => 'jpgm',
                                 'href' => 'jp-gm.html',
                                 'label' => 'Gruppenmeisterschaft',
                             ],
                             [
                                 'id' => 'jpvs',
                                 'href' => 'jp-vs.html',
                                 'label' => 'Versammlungen',
                             ],
                             [
                                 'id' => 'jpvt',
                                 'href' => 'jp-vt.html',
                                 'label' => 'Veteranen',
                             ],
                             [
                                 'id' => 'jpwb',
                                 'href' => 'jp-wb.html',
                                 'label' => 'Ausbildungen',
                             ],
                         ],
                     ],
                     [
                         'id' => 'bundesprogramm',
                         'href' => 'bpinfo.html',
                         'label' => 'Bundesprogramm',
                         'subNavigation' => [
                             [
                                 'id' => 'bpinfo',
                                 'href' => 'bpinfo.html',
                                 'label' => 'Info',
                             ],
                             [
                                 'id' => 'bpdaten',
                                 'href' => 'bundesprogramm.html',
                                 'label' => 'Daten',
                             ],
                         ],
                     ],
                     [
                         'id' => 'fs',
                         'href' => 'fsv.html',
                         'label' => 'Feldschiessen',
                         'subNavigation' => [
                             [
                                 'id' => 'fsv',
                                 'href' => 'fsv.html',
                                 'label' => 'Feldschiessen',
                             ],
                             [
                                 'id' => 'fsd',
                                 'href' => 'fsd.html',
                                 'label' => 'Daten',
                             ],
                         ],
                     ],
                     [
                         'id' => 'js',
                         'href' => 'jsv.html',
                         'label' => 'Jungschützen/Nachwuchs',
                         'subNavigation' => [
                             [
                                 'id' => 'jsv',
                                 'href' => 'jsv.html',
                                 'label' => 'Infos',
                             ],
                             [
                                 'id' => 'jsgm',
                                 'href' => 'jsgm.html',
                                 'label' => 'Gruppenmeisterschaft',
                             ],
                             [
                                 'id' => 'jstag',
                                 'href' => 'jstag.html',
                                 'label' => 'Jungschützentag',
                             ],
                         ],
                     ],
                     [
                         'id' => 'mw',
                         'href' => 'mwv.html',
                         'label' => 'Matchwesen',
                         'subNavigation' => [
                             [
                                 'id' => 'mwv',
                                 'href' => 'mwv.html',
                                 'label' => 'Vorwort',
                             ],
                             [
                                 'id' => 'mwberichte',
                                 'href' => 'mwberichte.html',
                                 'label' => 'Infos/Diverses',
                             ],
                             [
                                 'id' => 'mwranglisten',
                                 'href' => 'mwranglisten.html',
                                 'label' => 'Ranglisten',
                             ],
                         ],
                     ],
                     [
                         'id' => 'm',
                         'href' => 'gmv.html',
                         'label' => 'Gruppenmeisterschaft',
                         'subNavigation' => [
                             [
                                 'id' => 'gmv',
                                 'href' => 'gmv.html',
                                 'label' => 'Vorwort',
                             ],
                             [
                                 'id' => 'gmstart',
                                 'href' => 'gmstart.html',
                                 'label' => 'Startlisten',
                             ],
                             [
                                 'id' => 'gmrang',
                                 'href' => 'gmrang.html',
                                 'label' => 'Ranglisten',
                             ],
                         ],
                     ],
                     [
                         'id' => 'anlaesse',
                         'href' => 'sav.html',
                         'label' => 'Schiessanlässe',
                         'subNavigation' => [
                             [
                                 'id' => 'sav',
                                 'href' => 'sav.html',
                                 'label' => 'Vorwort',
                             ],
                             [
                                 'id' => 'wyberschuessae',
                                 'href' => 'wyberschuessae.html',
                                 'label' => 'ZU Wyberschüssä',
                             ],
                             [
                                 'id' => 'ktsf',
                                 'href' => 'ktsf.html',
                                 'label' => 'Kant. Schützenfeste',
                             ],
                             [
                                 'id' => 'bezirksm',
                                 'href' => 'bezirksm.html',
                                 'label' => 'Bezirksmeisterschaft',
                             ],
                             [
                                 'id' => 'bezirkss',
                                 'href' => 'bezirkss.html',
                                 'label' => 'Bezirksschiessen',
                             ],
                             [
                                 'id' => 'jugends',
                                 'href' => 'jugends.html',
                                 'label' => 'Jugendschiessen',
                             ],
                         ],
                     ],
                     [
                         'id' => 'veteranen',
                         'href' => 'veteranen.html',
                         'label' => 'Veteranen',
                         'subNavigation' => [],
                     ],
                     [
                         'id' => 'stuetzpunkttraining',
                         'href' => 'stuetzpunkttraining.html',
                         'label' => 'Stützpunkttraining ZHSV',
                         'subNavigation' => [],
                     ],
                     [
                         'id' => 'weiterbildung',
                         'href' => 'weiterbildung.html',
                         'label' => 'Ausbildungen',
                         'subNavigation' => [],
                     ],
                     [
                         'id' => 'login',
                         'href' => '/backend/',
                         'label' => 'Login/Registrieren',
                         'subNavigation' => [],
                     ],
                 ] as $item
        ) {
            $htmlDataObject = new HtmlDataObject();
            $htmlDataObject->addTextElement(propertyName: 'id', content: $item['id'], isEncodedForRendering: true);
            $htmlDataObject->addTextElement(propertyName: 'href', content: $item['href'], isEncodedForRendering: true);
            $htmlDataObject->addTextElement(propertyName: 'label', content: $item['label'], isEncodedForRendering: true);
            if (count(value: $item['subNavigation']) === 0) {
                $htmlDataObject->addHtmlDataObjectsArray(
                    propertyName: 'subNavigation',
                    htmlDataObjectsArray: null
                );
            } else {
                $subNavigation = new HtmlDataObjectCollection();
                foreach ($item['subNavigation'] as $subItem) {
                    $subObject = new HtmlDataObject();
                    $subObject->addTextElement(propertyName: 'id', content: $subItem['id'], isEncodedForRendering: true);
                    $subObject->addTextElement(propertyName: 'href', content: $subItem['href'], isEncodedForRendering: true);
                    $subObject->addTextElement(propertyName: 'label', content: $subItem['label'], isEncodedForRendering: true);
                    $subNavigation->add(htmlDataObject: $subObject);
                }
                $htmlDataObject->addHtmlDataObjectsArray(
                    propertyName: 'subNavigation',
                    htmlDataObjectsArray: $subNavigation->items
                );
            }
            $navigation->add(htmlDataObject: $htmlDataObject);
        }

        return $navigation;
    }
}