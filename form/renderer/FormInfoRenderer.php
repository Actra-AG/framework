<?php
/**
 * @author    Christof Moser <framework@actra.ch>
 * @copyright Actra AG, Rümlang, Switzerland
 */

namespace framework\form\renderer;

use framework\form\component\FormInfo;
use framework\form\FormRenderer;
use framework\html\HtmlTag;
use framework\html\HtmlTagAttribute;

class FormInfoRenderer extends FormRenderer
{
	private FormInfo $formInfo;

	public function __construct(FormInfo $formInfo)
	{
		$this->formInfo = $formInfo;
	}

	public function prepare(): void
	{
		$formInfo = $this->formInfo;

		$dtTag = new HtmlTag('dt', false);
		$dtClasses = $formInfo->getDtClasses();
		if (count($dtClasses) > 0) {
			$dtTag->addHtmlTagAttribute(new HtmlTagAttribute('class', implode(' ', $dtClasses), true));
		}
		$dtTag->addText($formInfo->getTitle());

		$ddTag = new HtmlTag('dd', false);
		$ddClasses = $formInfo->getDdClasses();
		if (count($ddClasses) > 0) {
			$ddTag->addHtmlTagAttribute(new HtmlTagAttribute('class', implode(' ', $ddClasses), true));
		}
		$ddTag->addText($formInfo->getContent());

		$dlTag = new HtmlTag('dl', false);
		$dlClasses = $formInfo->getDlClasses();
		if (count($dlClasses) > 0) {
			$dlTag->addHtmlTagAttribute(new HtmlTagAttribute('class', implode(' ', $dlClasses), true));
		}
		$dlTag->addTag($dtTag);
		$dlTag->addTag($ddTag);

		$this->setHtmlTag($dlTag);
	}
}