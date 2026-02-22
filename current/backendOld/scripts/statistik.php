<?php namespace backend\scripts;

use classes\pageClass;

class statistik extends pageClass
{
	public function execute() {
		$this->placeholders['statistik'] = "http://www.bsv-buelach.ch/webstat/";

	}
}

/* EOF */