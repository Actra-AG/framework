<?php
namespace scripts;

use classes\pageClass;

class template1 extends pageClass
{
	public function execute()
	{
		$rand = rand(1, 28);

		$copyright = '2006';
		if ($copyright < date("Y")) {
			$copyright .= ' - ' . date("Y");
		}

		$this->placeholders['rand'] = $rand;
		$this->placeholders['copyright'] = $copyright;
	}
}
/* EOF */