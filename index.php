<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

use metanet\db\DBConnect;
use metanet\db\DBMySQL;
use classes\ErrorHandler;
use classes\Registry;
use classes\RequestHandler;
use classes\ExceptionHandler;
use classes\ShowFile;
use classes\ShowPage;

# ------------------
# load configuration
# ------------------

require_once('config.php');

# ----------------
# autoload classes
# ----------------

function __autoload($class_name)
{
	$path = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT'] . '/' . $class_name . '.php');
	if (file_exists($path)) {
		require_once $path;
	}
}

# --------------------------------------------------------
# handle default errors (that occur outside our framework)
# --------------------------------------------------------

if (isset($_GET['default_error'])) {
	ErrorHandler::display_error($_GET['default_error']);
}

# --------------------
# get current protocol
# --------------------

if (isset($_SERVER['https']) && $_SERVER['https'] == 1) { /* Apache */
	$config['protocol'] = 'https';
} else if (isset($_SERVER['https']) && $_SERVER['https'] == 'on') { /* IIS */
	$config['protocol'] = 'https';
} else if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) { /* others */
	$config['protocol'] = 'https';
} else { /* just using http */
	$config['protocol'] = 'http';
}

# -------------------------------------------------
# use individual domain for every country/language?
# -------------------------------------------------

if (isset($config['envArr'][$_SERVER['SERVER_NAME']])) {
	$config['env'] = $config['envArr'][$_SERVER['SERVER_NAME']];
} else {
	header("Location: {$config['defaultURI']}");
	exit;
}

# ---------------------------
# define environment settings
# ---------------------------

if ($config['env']) {
	$config['DB'] = $config[$config['env']]['DB'];
	$config['requireSSL'] = $config[$config['env']]['requireSSL'];
	$config['debug'] = $config[$config['env']]['debug'];
}

# ---------
# check SSL
# ---------

if ($config['requireSSL'] && $config['protocol'] == 'http') {
	RequestHandler::redirect("https://{$_SERVER['SERVER_NAME']}/");
}

# ------------------
# set error handling
# ------------------

error_reporting($config['errorReporting']);
set_error_handler([new ErrorHandler(), 'php_error']);

# ------------
# localization
# ------------

setlocale(LC_ALL, $config['locale']);
date_default_timezone_set($config['default_timezone']);

# ----------------------------------
# save the configuration to Registry
# ----------------------------------

Registry::set('CONFIG', $config);

# ----------------------
# set exception handling
# ----------------------

$ExceptionHandler = new ExceptionHandler();
Registry::set('EXCEPTION_HANDLER', $ExceptionHandler);

# ------------------
# Initialize request
# ------------------

$requestHandler = new RequestHandler();
$requestHandler->initRequest();

if ($requestHandler->reqType == 'file') {
	# ---------
	# show file
	# ---------

	$showFile = new ShowFile();
	$showFile->config = $requestHandler->config;
	$showFile->reqArr = $requestHandler->reqArr;
	$showFile->output();
} else {
	# ---------------
	# service or page
	# ---------------

	# connect to db
	$DB_LINK = null;

	if ($config['useDB'] === true) {

		$dbConnect = new DBConnect($config['DB']['hostname'], $config['DB']['database'],
			$config['DB']['username'], $config['DB']['password'], 'UTF8');
		$DB_LINK = new DBMySQL($dbConnect);
		$DB_LINK->exec("SET NAMES 'utf8'");
		$DB_LINK->exec("SET CHARACTER SET utf8");
	}
	Registry::set('DB', $DB_LINK);

	//get rid of warning "The session id contains invalid characters, valid characters are only a-z, A-Z and 0-9"
	$sn = session_name();
	if (isset($_GET[$sn])) {
		if (strlen($_GET[$sn]) != 32 && strlen($_GET[$sn]) != 26) {
			unset($_GET[$sn]);
		}
	}
	if (isset($_POST[$sn])) {
		if (strlen($_POST[$sn]) != 32 && strlen($_POST[$sn]) != 26) {
			unset($_POST[$sn]);
		}
	}
	if (isset($_COOKIE[$sn])) {
		if (strlen($_COOKIE[$sn]) != 32 && strlen($_COOKIE[$sn]) != 26) {
			unset($_COOKIE[$sn]);
		}
	}
	if (isset($PHPSESSID)) {
		if (strlen($PHPSESSID) != 32 && strlen($PHPSESSID) != 26) {
			unset($PHPSESSID);
		}
	}

	@session_start();

	if ($requestHandler->reqType == 'service' && $requestHandler->serviceName) {

		$className = 'services\\' . $requestHandler->serviceName;
		/** @var \classes\serviceClass $serviceClass */
		$serviceClass = new $className($DB_LINK, $requestHandler);
		echo $serviceClass->execute();
	} else {
		# show page
		$platzhalter = [];

		$showPage = new ShowPage();
		$showPage->config = $requestHandler->getVar("config");
		$showPage->reqArr = $requestHandler->getVar("reqArr");
		$showPage->arrVars = $requestHandler->getVar("arrVars");
		$showPage->intAccess = $requestHandler->getVar("intAccess");
		$showPage->userData = $requestHandler->getVar("userData");

		$showPage->getConfig();
		$showPage->getContent();
		if ($showPage->checkScripts()) {
			$dynPage = str_replace([$_SERVER['DOCUMENT_ROOT'] . '/', '/', '.php'], ['', '\\', ''], $showPage->pageArr['dynPage']);

			/** @var \classes\pageClass $pageClass */
			$pageClass = new $dynPage($DB_LINK, $requestHandler, $showPage);
			$pageClass->execute();
			$platzhalter = array_merge($pageClass->getPlaceholders(), $platzhalter);
		}
		if ($showPage->getTemplate()) {
			$dynTemplate = str_replace([$_SERVER['DOCUMENT_ROOT'] . '/', '/', '.php'], ['', '\\', ''], $showPage->pageArr['dynTemplate']);

			/** @var \classes\pageClass $pageClass */
			$pageClass = new $dynTemplate($DB_LINK, $requestHandler, $showPage);
			$pageClass->execute();
			$platzhalter = array_merge($pageClass->getPlaceholders(), $platzhalter);
		}
		if (isset($platzhalter)) {
			$showPage->pageArr['platzhalter'] = array_merge($showPage->pageArr['platzhalter'], $platzhalter);
		}
		$showPage->output();
	}

	# close session, disconnect from db and exit
	$requestHandler->finish();
}
/* EOF */