<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view;

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
		$replacements = HtmlDocument::get()->replacements;
		$replacements->addEncodedText(identifier: 'randomHeaderImage', content: rand(min: 1, max: 28));
		$replacements->addHtmlDataObjectCollection(identifier: 'navigation', htmlDataObjectCollection: $this->navigation());
		parent::__construct(
			requiredViewGroupName: 'frontend',
			ipWhitelist: [],
			authUser: null,
			requiredAccessRights: AccessRightCollection::createEmpty(),
			inputParameterCollection: new InputParameterCollection()
		);
	}

	private function navigation(): HtmlDataObjectCollection
	{
		$navigation = new HtmlDataObjectCollection();
		foreach ([
			[
				'id'            => 'start',
				'href'          => 'start.html',
				'label'         => 'Startseite',
				'subNavigation' => [
					[
						'id'    => 'newsarchiv',
						'href'  => 'newsArchiv.html',
						'label' => 'Newsarchiv',
					],
				],
			],
			[
				'id'            => 'vorstand',
				'href'          => 'vorstand.html',
				'label'         => 'Vorstand',
				'subNavigation' => [
					[
						'id'    => 'ehrenmitglieder',
						'href'  => 'ehrenmitglieder.html',
						'label' => 'Ehrenmitglieder',
					],
				],
			],
			[
				'id'            => 'vereine',
				'href'          => 'vereine300.html',
				'label'         => 'Vereine BSVB',
				'subNavigation' => [
					[
						'id'    => 'vereine300',
						'href'  => 'vereine300.html',
						'label' => '300m Sektionen',
					],
					[
						'id'    => 'vereine25',
						'href'  => 'vereine25.html',
						'label' => '25/50m Sektionen',
					],
					[
						'id'    => 'vereine300',
						'href'  => 'vereine300.html',
						'label' => '300m Sektionen',
					],
					[
						'id'    => 'vereine300',
						'href'  => 'vereine300.html',
						'label' => '300m Sektionen',
					],
				],
			],
		] as $item) {
			$htmlDataObject = new HtmlDataObject();
			$htmlDataObject->addTextElement(propertyName: 'id', content: $item['id'], isEncodedForRendering: true);
			$htmlDataObject->addTextElement(propertyName: 'href', content: $item['href'], isEncodedForRendering: true);
			$htmlDataObject->addTextElement(propertyName: 'label', content: $item['label'], isEncodedForRendering: true);
			if (count(value: $item['subNavigation']) === 0) {
				$subNavigation = null;
			} else {
				$subNavigation = new HtmlDataObjectCollection();
				foreach ($item['subNavigation'] as $subItem) {
					$subObject = new HtmlDataObject();
					$subObject->addTextElement(propertyName: 'id', content: $subItem['id'], isEncodedForRendering: true);
					$subObject->addTextElement(propertyName: 'href', content: $subItem['href'], isEncodedForRendering: true);
					$subObject->addTextElement(propertyName: 'label', content: $subItem['label'], isEncodedForRendering: true);
					$subNavigation->add(htmlDataObject: $subObject);
				}
			}
			$htmlDataObject->addHtmlDataObjectsArray(propertyName: 'subNavigation', htmlDataObjectsArray: $subNavigation->getItems());
			$navigation->add(htmlDataObject: $htmlDataObject);
		}

		return $navigation;
	}
	/*
                <li id="vereine"><a href="vereine300.html">Vereine BSVB</a>
                    <!-- sub_vereine START -->
                    <ul>
                        <li id="vereine300"><a href="vereine300.html">300m Sektionen</a></li>
                        <li id="vereine25"><a href="vereine25.html">25/50m Sektionen</a></li>
                        <li id="vereinejs"><a href="vereinejs.html">Jungschützenkurse</a></li>
                        <li id="vereinedo"><a href="vereinedo.html">Div. Organisationen</a></li>
                    </ul>
                    <!-- sub_vereine ENDE -->
                </li>
                <li id="jahresprogramm"><a href="jpAll.html">Jahresprogramm</a>
                    <!-- sub_jahresprogramm START -->
                    <ul>
                        <li id="jpAll"><a href="jpAll.html">Alle Anlässe</a></li>
                        <li id="jpsa"><a href="jp-sa.html">Schiessanlässe</a></li>
                        <li id="jpjs"><a href="jp-js.html">Jungschützen/Nachwuchs</a></li>
                        <li id="jpmw"><a href="jp-mw.html">Matchwesen</a></li>
                        <li id="jpgm"><a href="jp-gm.html">Gruppenmeisterschaft</a></li>
                        <li id="jpvs"><a href="jp-vs.html">Versammlungen</a></li>
                        <li id="jpvt"><a href="jp-vt.html">Veteranen</a></li>
                        <li id="jpwb"><a href="jp-wb.html">Ausbildungen</a></li>
                    </ul>
                    <!-- sub_jahresprogramm ENDE -->
                </li>
                <li id="bundesprogramm"><a href="bpinfo.html">Bundesprogramm</a>
                    <!-- sub_bundesprogramm START -->
                    <ul>
                        <li id="bpinfo"><a href="bpinfo.html">Info</a></li>
                        <li id="bpdaten"><a href="bundesprogramm.html">Daten</a></li>
                    </ul>
                    <!-- sub_bundesprogramm ENDE -->
                </li>
                <li id="fs"><a href="fsv.html">Feldschiessen</a>
                    <!-- sub_fs START -->
                    <ul>
                        <li id="fsv"><a href="fsv.html">Feldschiessen</a></li>
                        <li id="fsd"><a href="fsd.html">Daten</a></li>
                    </ul>
                    <!-- sub_fs ENDE -->
                </li>
                <li id="js"><a href="jsv.html">Jungschützen/Nachwuchs</a>
                    <!-- sub_js START -->
                    <ul>
                        <li id="jsv"><a href="jsv.html">Infos</a></li>
                        <!--<li>   <li id="jsrap"><a href="jsrap.html">Rapporte</a></li>-->
                        <li id="jsgm"><a href="jsgm.html">Gruppenmeisterschaft</a></li>
                        <li id="jstag"><a href="jstag.html">Jungschützentag</a></li>
                    </ul>
                    <!-- sub_js ENDE -->
                </li>
                <li id="mw"><a href="mwv.html">Matchwesen</a>
                    <!-- sub_mw START -->
                    <ul>
                        <li id="mwv"><a href="mwv.html">Vorwort</a></li>
                        <li id="mwberichte"><a href="mwberichte.html">Infos/Diverses</a></li>
                        <li id="mwranglisten"><a href="mwranglisten.html">Ranglisten</a></li>
                    </ul>
                    <!-- sub_mw ENDE -->
                </li>
                <li id="m"><a href="gmv.html">Gruppenmeisterschaft</a>
                    <!-- sub_gm START -->
                    <ul>
                        <li id="gmv"><a href="gmv.html">Vorwort</a></li>
                        <li id="gmstart"><a href="gmstart.html">Startlisten</a></li>
                        <li id="gmrang"><a href="gmrang.html">Ranglisten</a></li>
                    </ul>
                    <!-- sub_gm ENDE -->
                </li>
                <li id="anlaesse"><a href="sav.html">Schiessanlässe</a>
                    <!-- sub_anlaesse START -->
                    <ul>
                        <li id="sav"><a href="sav.html">Vorwort</a></li>
                        <li id="wyberschuessae"><a href="wyberschuessae.html">ZU Wyberschüssä</a></li>
                        <li id="ktsf"><a href="ktsf.html">Kant. Schützenfeste</a></li>
                        <li id="bezirksm"><a href="bezirksm.html">Bezirksmeisterschaft</a></li>
                        <li id="bezirkss"><a href="bezirkss.html">Bezirksschiessen</a></li>
                        <li id="jugends"><a href="jugends.html">Jugendschiessen</a></li>
                    </ul>
                    <!-- sub_anlaesse ENDE -->
                </li>
                <!--  </li>-->
                <li id="veteranen"><a href="veteranen.html">Veteranen</a></li>
                <li id="stuetzpunkttraining"><a href="stuetzpunkttraining.html">Stützpunkttraining ZHSV</a></li>
                <li id="weiterbildung"><a href="weiterbildung.html">Ausbildungen</a></li>
                <li id="login"><a href="/backend/">Login/Registrieren</a></li>
	 * */
}