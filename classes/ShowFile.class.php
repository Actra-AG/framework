<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

class ShowFile
{

	public $config;
	public $reqArr;

	function output()
	{
		$getFile = $_SERVER['DOCUMENT_ROOT'] . $this->reqArr['varDirectories'] . $this->reqArr['varFiletitle'] . '.' . $this->reqArr['varFileext'];

		$fileExtLower = strtolower($this->reqArr['varFileext']);
		if (!file_exists($getFile)) {
			ErrorHandler::display_error(404);

		} else {
			switch ($fileExtLower) {
				case "txt":
					$ctype = "text/plain";
					break;
				case "log":
					$ctype = "text/plain";
					break;
				case "css":
					$ctype = "text/css";
					break;
				case "jpg":
					$ctype = "image/jpeg";
					break;
				case "gif":
					$ctype = "image/gif";
					break;
				case "png":
					$ctype = "image/png";
					break;
				case "xls":
					$ctype = "application/vnd.ms-excel";
					break;
				case "xla":
					$ctype = "application/vnd.ms-excel";
					break;
				case "ppt":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "pps":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "ppz":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "pot":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "doc":
					$ctype = "application/msword";
					break;
				case "dot":
					$ctype = "application/msword";
					break;
				case "pdf":
					$ctype = "application/pdf";
					break;
				case "js":
					$ctype = "application/x-javascript";
					break;
				case "zip":
					$ctype = "application/zip";
					break;
				case "exe":
					$ctype = "application/octet-stream";
					break;
				case "swf":
					$ctype = "application/x-shockwave-flash";
					break;
				default:
					$ctype = "application/octet-stream";
					break;
			}

			header("Cache-Control: maxage=1"); //In seconds
			header("Pragma: public");
			header("Content-Type: {$ctype}");

			// change, added quotes to allow spaces in filenames, by Rajkumar Singh
			header("Content-Disposition: attachment; filename=\"" . basename($getFile) . "\";");
			readfile($getFile);
			exit;
		}
	}
}

/* EOF */