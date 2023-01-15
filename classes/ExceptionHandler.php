<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 22.07.2009	CM	updated
# 26.05.2009	DM	created

namespace classes;

class ExceptionHandler
{
	private $errors;

	function __construct()
	{
		$fn = $_SERVER['DOCUMENT_ROOT'] . '/errors/defaultExceptions.php';
		if (file_exists($fn)) {
			$this->refreshErrorList($fn);
		}
	}

	function throw_error($error, $more = '')
	{
		global $config;

		if (isset($this->errors[$error])) {
			$message = $this->errors[$error] . "<br/>" . $more;
		} else {
			$message = 'UNDEFINED ERROR MESSAGE';
		}

		ob_start();
		debug_print_backtrace();
		$message .= "\n\n\nBacktrace:\n" . ob_get_contents();
		ob_end_clean();

		if ($config['debug']) {
			echo "<pre>{$message}</pre>";
			session_write_close();
			exit;
		} else {
			error_log($message, 1, $config['errorEmail']);
			ErrorHandler::display_error(500);
		}
	}

	function refreshErrorList($errorFile)
	{
		$stream = fopen(filename: $errorFile, mode: 'r');
		while ($line = fgets(stream: $stream)) {
			$parts = explode("||", $line);
			$partKey = trim($parts[0], "\x7f..\xff\x0..\x1f ");
			if ($partKey != "") {
				$this->errors[$partKey] = trim($parts[1], "\x7f..\xff\x0..\x1f");
			}
		}
	}
}