<?php namespace backend\scripts;

use classes\pageClass;
use classes\navigator;

class template1 extends pageClass
{
	public function execute()
	{
		$copyright = '2009';
		$tpllogon = '';
		$breadcrumb = '';

		if ($copyright < date("Y")) {
			$copyright .= ' - ' . date("Y");
		}

		if ($this->showPage->checkAccess() && isset($this->showPage->userData->vorname)) {
			$tpllogon = "<strong>{$this->showPage -> userData -> vorname} {$this->showPage -> userData -> nachname}</strong>";
		}

		if (isset($this->showPage->pageArr['grundkonf']['navigator']['use']) && $this->showPage->pageArr['grundkonf']['navigator']['use']) {
			$navigator = new navigator($this->showPage->arrVars, $this->showPage->pageArr['navistufe']);
			if (isset($this->showPage->pageArr['grundkonf']['navigator']['reset']) && $this->showPage->pageArr['grundkonf']['navigator']['reset']) {
				$navigator->resetBreadcrumb();
			}
			$navigator->addBreadcrumb($this->showPage->pageArr['platzhalter']['title']);
			$this->showPage->pageArr['navistufe'] = $navigator->setNavistufe();
			$breadcrumb = $navigator->getBreadcrumb();
		}

		$this->placeholders['copyright'] = $copyright;
		$this->placeholders['tpllogon'] = $tpllogon;
		$this->placeholders['breadcrumb'] = $breadcrumb;
	}
}
/* EOF */