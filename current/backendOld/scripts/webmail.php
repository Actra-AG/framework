<?php namespace backend\scripts;

use classes\pageClass;

class webmail extends pageClass
{
	public function execute() {
		if ($this->showPage->checkUG('aktiv')) {
			$this->placeholders['webmail'] = 'http://webmail.bsv-buelach.ch/';
		}

	}
}

/* EOF */