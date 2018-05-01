<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	created, replacement of globalFunctions.class.php

namespace classes;
use stdClass;

use metanet\db\DBMySQL;

class RequestHandler
{
	public $config;
	public $reqType;
	public $reqArr;
	public $arrVars;
	public $accessChecked;
	/** @var stdClass */
	public $userData;
	public $serviceName;
	/** @var DBMySQL */
	static private $DB_LINK;
	public $intAccess;

	function __construct()
	{
		self::$DB_LINK = Registry::get('DB');

		$this->config = Registry::get('CONFIG');
		$this->reqType = 'undefined';
		$this->reqArr = [];
		$this->arrVars = [];
		$this->accessChecked = false;
		$this->intAccess = false;
		$this->userData = [];
	}

	/**
	 * @param string $url
	 */
	public static function redirect($url = "http://www.actra.ch")
	{
		$config = Registry::get('CONFIG');
		$c = parse_url($url);
		if (!array_key_exists('host', $c)) {
			$prot = $config['protocol'];
			$directory = dirname($_SERVER['REQUEST_URI']);
			$directory = str_replace('\\', '/', $directory);
			if ($directory == "/") {
				$directory = "";
			}
			$url = $prot . "://" . $_SERVER['SERVER_NAME'] . $directory . "/" . $url;
		}

		if (defined('SID') && SID !== "") {
			if (preg_match('/(.*)?(.+)=(.+)/', $url)) {
				$url = $url . "&" . SID;
			} else {
				$url = $url . "?" . SID;
			}
		}
		session_write_close();
		header("Location: {$url}");
		exit;
	}

	/***** initialize request *****/
	public function initRequest()
	{
		$reqArr = explode("/", $this->config['reqURI']);
		$reqCountArr = count($reqArr);
		$reqCountDir = $reqCountArr - 2;
		$reqFilenamePos = $reqCountArr - 1;
		$directories = '/';
		for ($z = 1; $z <= $reqCountDir; $z++) {
			if ($z == 1 && isset($this->config['services'][$reqArr[$z]])) {
				$this->reqType = 'service';
				$this->serviceName = $reqArr[$z];
			}
			$directories .= $reqArr[$z] . '/';
		}

		$arrVars = [];
		$varFiletitle = '';
		$varFileext = '';

		if ($this->reqType == 'undefined') {
			$fnFull = $reqArr[$reqFilenamePos];
			$fnFullArr = explode("?", $fnFull);
			$filename = $fnFullArr[0];

			if ($filename == "") {
				$this->reqType = 'page';
			} else {
				$fnArr = explode(".", $filename);
				$fnArrCount = count($fnArr);

				if ($fnArrCount != 2) {
					ErrorHandler::display_error(400);
				} else {
					$varFileext = $fnArr[1];

					if (strtolower($varFileext) == 'php') {
						ErrorHandler::display_error(400);
					} else if (strtolower($varFileext) == 'html') {
						$arrVars = explode("-", $fnArr[0]);
						$varFiletitle = $arrVars[0];
						if (strtolower($varFiletitle) == 'index') {
							ErrorHandler::display_error(400);
						} else {
							$this->reqType = 'page';
						}
					} else {
						$this->reqType = 'file';
						$varFiletitle = $fnArr[0];
					}
				}
			}

			if ($this->reqType == 'page') {
				$ok = 0;
				if ($directories == '/') {
					$ok = 1;
				} else if (isset($this->config['allowedDir']) && array_key_exists($directories, $this->config['allowedDir'])) {
					$this->config['defaultpage'] = $this->config['allowedDir'][$directories]['defaultpage'];
					$this->config['scriptsDir'] = $this->config['allowedDir'][$directories]['scriptsDir'];
					$this->config['rootDir'] = $this->config['allowedDir'][$directories]['rootDir'];
					$this->config['country'] = $this->config['allowedDir'][$directories]['country'];
					$this->config['language'] = $this->config['allowedDir'][$directories]['language'];

					$ok = 1;
				}

				if ($ok == 0) {
					ErrorHandler::display_error(404);
				} else {
					if ($varFiletitle == '') {
						$varFiletitle = $this->config['defaultpage'];
						$arrVars[0] = $varFiletitle;
					}
				}
			}
		}

		if ($this->reqType == 'undefined') {
			ErrorHandler::display_error(400);
		}

		$this->arrVars = $arrVars;

		$this->reqArr['varFiletitle'] = $varFiletitle;
		$this->reqArr['varFileext'] = $varFileext;
		$this->reqArr['varDirectories'] = $directories;
	}

	/***** check user privileges *****/
	private function accesscheck()
	{
		if (!$this->accessChecked) {
			$this->accessChecked = true;
			$this->intAccess = false;

			if (isset($_SESSION['intAccess']) && $_SESSION['intAccess'] && isset($_SESSION['userData'])) {
				$userAgent = '';
				if (isset($_SERVER['HTTP_USER_AGENT'])) {
					$userAgent = $_SERVER['HTTP_USER_AGENT'];
				}
				$remoteAddr = '';
				if (isset($_SERVER['REMOTE_ADDR'])) {
					$remoteAddr = $_SERVER['REMOTE_ADDR'];
				}
				if (!isset($_SESSION['userData']->accessEnv)) {
					$_SESSION['userData']->accessEnv = $userAgent . $remoteAddr;
				}
				if ($_SESSION['userData']->accessEnv == $userAgent . $remoteAddr) {
					$this->intAccess = true;
					$this->userData = $_SESSION['userData'];
				} else {
					unset($_SESSION['userData']);
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public function checkAccess()
	{
		$this->accesscheck();

		return $this->intAccess;
	}

	/**
	 * @param $ug
	 *
	 * @return bool
	 */
	public function checkUG($ug)
	{
		$this->accesscheck();

		return (isset($this->userData->$ug) && $this->userData->$ug == 1) ? true : false;
	}

	/***** log out *****/
	public function logOut()
	{
		if ($this->checkAccess()) {
			$this->intAccess = false;
			$this->userData = [];
			$_SESSION['intAccess'] = false;
			$_SESSION['userData'] = [];
		}
	}

	/***** regenerate sessionID *****/
	public function regenerate_sessionID()
	{
		session_regenerate_id();
	}

	/**
	 * @param $var
	 *
	 * @return bool
	 */
	public function getVar($var)
	{
		return (isset($this->$var)) ? $this->$var : false;
	}

	/**
	 * @param $addr
	 *
	 * @return bool
	 */
	function valemail($addr)
	{

		if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * @param       $addr
	 * @param array $options
	 *
	 * @return bool
	 */
	function valurl($addr, $options = [])
	{

		if (!filter_var($addr, FILTER_VALIDATE_URL, $options)) {
			return false;
		} else {
			return true;
		}
	}

	/***** finish request *****/
	public function finish()
	{
		session_write_close();
		if ($this->config['useDB'] == 1) {
			Registry::remove('DB');
		}
		exit;
	}
}

/* EOF */