<?php
/**
 * @author METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2015, METANET AG
 */

$mnauth = false;
if (isset($_GET['mnauth']) && isset($_SESSION['mnauth']['access']) && $_SESSION['mnauth']['access'] == true) {
	$mnauth = true;
}

/* EOF */