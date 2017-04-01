<?php
$rand = '';

$rand = rand(1, 28);

$copyright = '2006';
if ($copyright < date("Y")) {
	$copyright .= ' - ' . date("Y");
}

$platzhalter['rand'] = $rand;
$platzhalter['copyright'] = $copyright;
/* EOF */