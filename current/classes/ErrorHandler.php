<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	transfered into a class

namespace classes;

class ErrorHandler
{
	/**
	 * @param string $errCode
	 */
	public static function display_error($errCode = 'undefined')
	{
		switch ($errCode) {
			case 400:
				header("HTTP/1.1 400 Bad Request");
				break;
			case 401:
				header("HTTP/1.1 401 Unauthorized");
				break;
			case 403:
				header("HTTP/1.1 403 Forbidden");
				break;
			case 405:
				header("HTTP/1.1 405 Method Not Allowed");
				break;
			case 406:
				header("HTTP/1.1 406 Not Acceptable");
				break;
			case 407:
				header("HTTP/1.1 407 Proxy Authentication Required");
				break;
			case 408:
				header("HTTP/1.1 408 Request Time-out");
				break;
			case 409:
				header("HTTP/1.1 409 Conflict");
				break;
			case 410:
				header("HTTP/1.1 410 Gone");
				break;
			case 411:
				header("HTTP/1.1 411 Length Required");
				break;
			case 412:
				header("HTTP/1.1 412 Precondition Failed");
				break;
			case 413:
				header("HTTP/1.1 413 Request Entity Too Large");
				break;
			case 414:
				header("HTTP/1.1 414 Request-URI Too Long");
				break;
			case 415:
				header("HTTP/1.1 415 Unsupported Media Type");
				break;
			case 500:
				header("HTTP/1.1 500 Internal Server Error");
				break;
			case 501:
				header("HTTP/1.1 501 Not Implemented");
				break;
			case 502:
				header("HTTP/1.1 502 Bad Gateway");
				break;
			case 503:
				header("HTTP/1.1 503 Service Unavailable");
				break;
			case 504:
				header("HTTP/1.1 504 Gateway Time-out");
				break;
			case 505:
				header("HTTP/1.1 505 HTTP Version not supported");
				break;
			default:
				header("HTTP/1.1 404 Not found");
				break;
		}

		$path = $_SERVER['DOCUMENT_ROOT'] . '/errors/' . $errCode . '.php';
		if (file_exists($path)) {
			require_once($path);
		} else {
			echo "<p>Leider ist ein Fehler aufgetreten. <a href=\"http://{$_SERVER['SERVER_NAME']}\">weiter</a></p>";
		}
		session_write_close();
		exit;
	}

	public static function php_error(int $e_number, string $e_message, string $e_file, int $e_line)
	{
		global $config;

		// build the error message
		$message = "Error/Warning #{$e_number} in script '{$e_file}' on line {$e_line}: \n{$e_message}\n";

		// add the date and time
		$message .= "Date/Time: " . date('d.m.Y H:i:s') . "\n";
		ob_start();
		debug_print_backtrace();
		$trace = ob_get_contents();
		ob_end_clean();

		$message .= "\n\n\nBacktrace: \n{$trace}";

		if ($config['debug']) {
			echo "<pre>{$message}</pre>";
			session_write_close();
			exit;
		} else {
			error_log($message, 1, $config['errorEmail']);
			self::display_error(500);
		}
	}
}