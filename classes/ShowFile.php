<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

namespace classes;

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
			$ctype = match ($fileExtLower) {
				"txt", "log" => "text/plain",
				"css" => "text/css",
				"jpg" => "image/jpeg",
				"gif" => "image/gif",
				"png" => "image/png",
				"xls", "xla" => "application/vnd.ms-excel",
				"ppt", "ppz", "pot", "pps" => "application/vnd.ms-powerpoint",
				"doc", "dot" => "application/msword",
				"pdf" => "application/pdf",
				"js" => "application/x-javascript",
				"zip" => "application/zip",
				"swf" => "application/x-shockwave-flash",
				default => "application/octet-stream",
			};

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