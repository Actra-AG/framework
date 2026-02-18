<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace site\settings;

use framework\html\HtmlDataObjectCollection;
use site\libs\navigation\NavigationItem;

class Navigation
{
    public const array DATA = [
        [
            'id' => 'start',
            'href' => 'start.html',
            'label' => 'Startseite',
            'subNavigation' => [
                [
                    'id' => 'newsarchiv',
                    'href' => 'newsArchiv.html',
                    'label' => 'Newsarchiv'
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
    ];

    public static function render(
        array $activeNavigationItems,
        int   $level
    ): HtmlDataObjectCollection
    {
        $navigation = new HtmlDataObjectCollection();
        foreach (Navigation::DATA as $item) {
            $navigationItem = new NavigationItem(
                name: $item['id'],
                url: $item['href'],
                label: $item['label'],
                subNavigation: array_key_exists(
                    key: 'subNavigation',
                    array: $item
                ) ? $item['subNavigation'] : []
            );
            $navigation->add(
                htmlDataObject: $navigationItem->render(
                    activeNavigationItems: $activeNavigationItems,
                    level: $level
                )
            );
        }

        return $navigation;
    }
}