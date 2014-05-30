<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 20.07.2009	CM	new version

class CountryLang {
	
	public $myDomain;
	public $env;
	public $country;
	public $language;
	public $rootDir;
	public $clArr;
	public $domainArr;
	private $config;
	private $settings;
	private $gotoArr;
	private $redirectArr;
	private $userCountry;
	private $userLang;
	private $userLangIsset;
	
	public function __construct($config) {
		$fn = $_SERVER['DOCUMENT_ROOT'].'/countryLang.php';
		if(file_exists($fn)) {
			require_once($fn);

		} else {
			ErrorHandler::display_error(500);
		}

		$this -> config = $config;
		$this -> settings = $clsettings;
		$this -> domainArr = $cldomainArr;
		$this -> gotoArr = $clgotoArr;
		$this -> env = 'live';
		$this -> myDomain = $_SERVER['SERVER_NAME'];
		$this -> userLangIsset = 0;
		
		if(!array_key_exists($this -> myDomain, $this -> domainArr['live']) && !array_key_exists($this -> myDomain, $this -> domainArr['dev']) && $this -> myDomain != $this -> settings['live'] && $this -> myDomain != $this -> settings['dev']) {
			$goto = $this -> gotoArr['default'];
			if(array_key_exists($this -> myDomain, $this -> gotoArr)) { $goto = $this -> gotoArr[$this -> myDomain]; }
    	header("HTTP/1.1 301 Moved Permanently");
    	$this -> redirect("{$goto}".$_SERVER['REQUEST_URI']);
      exit;
		} else {

			if($this -> myDomain == $this -> settings['live'] || array_key_exists($this -> myDomain, $this -> domainArr['live'])) { $this -> env = 'live'; }
			if($this -> myDomain == $this -> settings['dev'] || array_key_exists($this -> myDomain, $this -> domainArr['dev'])) { $this -> env = 'dev'; }

  		if($_SERVER["REQUEST_URI"] == '/') {
        $this -> setLang();

	  	  if($this -> userLang != $this -> domainArr[$this -> env][$this -> myDomain]['language']) {
	  	  	$this -> autoRedirect();
			  }
		  }
		
			if($this -> myDomain == $this -> settings[$this -> env] && !array_key_exists($this -> myDomain, $this -> domainArr[$this -> env])) {
				$this -> autoRedirect();

			} else {
				$this -> country = $this -> domainArr[$this -> env][$this -> myDomain]['country'];
				$this -> language = $this -> domainArr[$this -> env][$this -> myDomain]['language'];
				$this -> rootDir = $this -> domainArr[$this -> env][$this -> myDomain]['rootDir'];
				$this -> defaultpage = $this -> domainArr[$this -> env][$this -> myDomain]['defaultpage'];
			}
		}
	}
	
	private function autoRedirect() {
  	$list = "";
		foreach($this -> domainArr[$this -> env] AS $key => $val) {
			$country = $val['country'];
			$language = $val['language'];
			$defaultpage = $val['defaultpage'];
  
  		$this -> redirectArr[$country.$language] = $key;

			$list .= "<li><a href=\"{$this -> protocol}://{$key}/{$defaultpage}.html\">".$this -> settings['countryArr'][$country]."</a> (".$this -> settings['languageArr'][$language].")</li>\n";
		}

    $this -> setCountry();
    $this -> setLang();
	  $x = $this -> userCountry.$this -> userLang;
	  if(array_key_exists($x, $this -> redirectArr)) {
	  	$y = $this -> redirectArr[$x];
//    	header("HTTP/1.1 300 Multiple Choices"); -> GEHT NICHT BEI OPERA!
      $this -> redirect($this -> redirectArr[$x]."/".$this -> domainArr[$this -> env][$y]['defaultpage'].'.html');
      exit;
	  }

		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
    echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" lang=\"de\" xml:lang=\"de\">\n";
    echo "<head>\n";
    echo "<title>{$this -> settings['titel']}</title>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\" />\n";
    echo "</head>\n";
    echo "<body>\n";
	  echo "<h2>{$this -> settings['titel']}</h2>\n<p>Bitte wählen Sie Ihr Land:</p><ul>\n";
		echo $list;
		echo "</ul>\n";
		echo "</body>\n";
		echo "</html>\n";
  	exit;


	}
	
	
	private function setCountry() {
  	if(count($this -> settings['countryArr']) <= 1 || !isset($_SERVER['REMOTE_ADDR'])) {
  		$this -> userCountry = 'XXX';
 		} else {
    	$this -> userCountry = strtoupper(file_get_contents('http://api.hostip.info/country.php?ip='.$_SERVER['REMOTE_ADDR']));
   	}
  	if(!array_key_exists($this -> userCountry, $this -> settings['countryArr'])) {
	    $this -> userCountry = key($this -> settings['countryArr']);
	  }
  }


  private function setLang($lang_variable = null, $strict_mode = true) {
  	if($this -> userLangIsset == 0) {
    	$this -> userLangIsset = 1;
  	  // $_SERVER['HTTP_ACCEPT_LANGUAGE'] verwenden, wenn keine Sprachvariable mitgegeben wurde
      if($lang_variable === null && isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      	$lang_variable = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
      }

      // wurde irgendwelche Information mitgeschickt?
      if(empty($lang_variable)) {
      	// Nein? => Standardsprache zurückgeben
    	  $this -> userLang = key($this -> settings['languageArr']);

      } else {
        // Den Header auftrennen
        $accepted_languages = preg_split('/,\s*/', $lang_variable);

        // Die Standardwerte einstellen
        $current_lang = key($this -> settings['languageArr']);
        $current_q = 0;

        // Nun alle mitgegebenen Sprachen abarbeiten
        foreach($accepted_languages as $accepted_language) {
        	// Alle Infos über diese Sprache rausholen
          $res = preg_match('/^([a-z]{1,8}(?:-[a-z]{1,8})*)'.'(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i', $accepted_language, $matches);
          // war die Syntax gültig?
          if (!$res) {
          	// Nein? Dann ignorieren
            continue;
          }
                
          // Sprachcode holen und dann sofort in die Einzelteile trennen
          $lang_code = explode ('-', $matches[1]);
      
          // Wurde eine Qualität mitgegeben?
          if(isset($matches[2])) {
          	// die Qualität benutzen
            $lang_quality = (float)$matches[2];
          } else {
          	// Kompabilitätsmodus: Qualität 1 annehmen
            $lang_quality = 1.0;
          }
      
          // Bis der Sprachcode leer ist...
          while(count($lang_code)) {
          	// mal sehen, ob der Sprachcode angeboten wird
            if(array_key_exists($lang_code[0], $this -> settings['languageArr'])) {
            	// Qualität anschauen
              if($lang_quality > $current_q) {
              	// diese Sprache verwenden
                $current_lang = strtolower($lang_code[0]);
                $current_q = $lang_quality;
                // Hier die innere while-Schleife verlassen
                break;
              }
            }
            // Wenn wir im strengen Modus sind, die Sprache nicht versuchen zu minimalisieren
            if($strict_mode) {
           	  // innere While-Schleife aufbrechen
              break;
            }
            // den rechtesten Teil des Sprachcodes abschneiden
            array_pop($lang_code);
          }
        }

        // die gefundene Sprache zurückgeben
        $this -> userLang = $current_lang;
      }
    }
  }
  
  private function redirect($to) {
  	if($this -> config[$this -> env]['requireSSL']) { $this -> config['protocol'] = 'https'; }
  	header("Location: {$this -> config['protocol']}://{$to}");
  	exit;
  }
}
?>