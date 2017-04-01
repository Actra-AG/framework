<?php
$copyright = '2009';
$tpllogon = '';
$breadcrumb = '';

if ($copyright < date("Y")) {
	$copyright .= ' - ' . date("Y");
}

if ($showPage->checkAccess() && isset($showPage->userData->vorname)) {
	$tpllogon = "<strong>{$showPage -> userData -> vorname} {$showPage -> userData -> nachname}</strong>";
}

if (isset($showPage->pageArr['grundkonf']['navigator']['use']) && $showPage->pageArr['grundkonf']['navigator']['use']) {
	$navigator = new navigator($showPage->arrVars, $showPage->pageArr['navistufe']);
	if (isset($showPage->pageArr['grundkonf']['navigator']['reset']) && $showPage->pageArr['grundkonf']['navigator']['reset']) {
		$navigator->resetBreadcrumb();
	}
	$navigator->addBreadcrumb($showPage->pageArr['platzhalter']['title']);
	$showPage->pageArr['navistufe'] = $navigator->setNavistufe();
	$breadcrumb = $navigator->getBreadcrumb();

}

$platzhalter['copyright'] = $copyright;
$platzhalter['tpllogon'] = $tpllogon;
$platzhalter['breadcrumb'] = $breadcrumb;
/* EOF */