<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 22.07.2009	CM	updated
# 26.05.2009	DM	created


class ExceptionHandler {

  var $errors;

  function __construct() {
  	$fn = $_SERVER['DOCUMENT_ROOT'].'/errors/defaultExceptions.php';
  	if(file_exists($fn)) {
      $this->refreshErrorList($fn);
  	}
  }

  function throw_error($error, $more='') {
  	global $config;

  	$message = "";
    if (isset($this->errors[$error])) {
      $message = $this->errors[$error]."<br/>".$more;
    } else {
    	$message = 'UNDEFINED ERROR MESSAGE';
    }

    ob_start();
    debug_print_backtrace();
    $message .= "\n\n\nBacktrace:\n".ob_get_contents();
    ob_end_clean();

	  if($config['debug']) {
  		echo "<pre>{$message}</pre>"; session_write_close(); exit;

  	} else {
		  error_log ($message, 1, $config['errorEmail']);
		  ErrorHandler::display_error(500);
		}


  }

  function refreshErrorList($errorFile) {
    $this->error_file = fopen($errorFile,"r");
    while ($line = fgets($this->error_file)) {
      $parts = explode("||", $line);
      $partKey = trim($parts[0],"\x7f..\xff\x0..\x1f ");
      if ($partKey!="") {
        $this->errors[$partKey] = trim($parts[1],"\x7f..\xff\x0..\x1f");
      }
    }
  }
}
?>