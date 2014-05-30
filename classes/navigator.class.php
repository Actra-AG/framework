<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 25.07.2009	CM	new version

class navigator {
	
	private $cp;
	private $cl;
	private $nsArr;
	
	public function __construct($av, $nsArr, $cp = '') {
		if($cp == '') {  $this -> cp = $av[0]; } else { $this -> cp = $cp; }
		$this -> cl = implode("-", $av);
		$this -> nsArr = $nsArr;
		if(isset($_GET['reset'])) { $this -> resetBreadcrumb(); }
	}
	
	public function resetBreadcrumb() {
		if(isset($_SESSION['sess_breadcrumb'])) { unset($_SESSION['sess_breadcrumb']); }
	}
	
	public function addBreadcrumb($title = '') {
		if(!isset($_SESSION['sess_breadcrumb'])) { $_SESSION['sess_breadcrumb'] = array(); }
		
//		if(!array_key_exists($this -> cp, $_SESSION['sess_breadcrumb'])) {
			$_SESSION['sess_breadcrumb'][$this -> cp]['title'] = $title;
			$_SESSION['sess_breadcrumb'][$this -> cp]['link'] = $this -> cl;
//		}
	}

  public function getBreadcrumb() {
  	$breadcrumb = '';
  	if(isset($_SESSION['sess_breadcrumb']) && is_array($_SESSION['sess_breadcrumb'])) {
  		$xArr = array();
  		$found = 0;
  		foreach($_SESSION['sess_breadcrumb'] AS $key => $val) {
  			if($key == $this -> cp) {
    			$xArr[] = "<strong>{$val['title']}</strong>";
    			$found = 1;
    		} else {
    			if($found == 0) {
      			$xArr[] = "<a href=\"{$val['link']}.html\">{$val['title']}</a>";
      		} else {
      			unset($_SESSION['sess_breadcrumb'][$key]);
      		}
    		}
  		}
  		$breadcrumb = "<p id=\"breadcrumb\">".implode(" &raquo; ", $xArr)."</p>";
    	if(count($_SESSION['sess_breadcrumb']) <= 1) { $breadcrumb = ''; }
  	}
  	return $breadcrumb;
  }
  
  public function setNavistufe() {
		if(!isset($_SESSION['sess_navistufe'])) { $_SESSION['sess_navistufe'] = array(); }
  	if(isset($_GET['n'])) { $_SESSION['sess_navistufe'] = explode("|", $_GET['n']); }
  	foreach($_SESSION['sess_navistufe'] AS $key => $val) {
  		$this -> nsArr[$key] = $val;
  	}

  	return $this -> nsArr;
  }
}
?>