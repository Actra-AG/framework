<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace site\view\frontend\php;

use framework\html\HtmlDocument;
use site\view\FrontendView;

class start extends FrontendView
{
	public function execute(): void
	{
		$htmlDocument = HtmlDocument::get();
		$htmlDocument->setActiveHtmlId(key: 1, val: 'start');
		$replacements = $htmlDocument->replacements;
		$replacements->addEncodedText(identifier: 'pageTitle', content: 'Startseite');
	}
}