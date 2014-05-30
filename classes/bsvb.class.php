<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 22.07.2009	CM	created

class bsvb {
	
	public $userArr;
	
	private $DB_LINK;
	private $config;
	
  function __construct() {
    $this -> DB_LINK = Registry::get('DB');
    $this -> config = Registry::get('CONFIG');
	}
	
	
	public function insertEntry($table, $fields = array()) {
		if(count($fields) == 0) { return 0; }
		$fArr = array(); foreach($fields AS $key => $val) { $fArr[] = "{$key}='{p}'"; }
		$sql = "INSERT INTO {p} SET ".implode(", ", $fArr);
		array_unshift($fields, $table);
		$qry = $this -> DB_LINK -> query($sql, $fields);
		return $qry -> getInsertId();

  }


  public function updateEntry($table, $entryID, $fields = array()) {
		if(count($fields) == 0) { return 0; }
		$fArr = array(); foreach($fields AS $key => $val) { $fArr[] = "{$key}='{p}'"; }
		$sql = "UPDATE {p} SET ".implode(", ", $fArr)." WHERE ID={p}";
		array_unshift($fields, $table);
		$fields[] = $entryID;
		$qry = $this -> DB_LINK -> query($sql, $fields);
		return $qry -> affected_rows();
  }


  public function deleteEntry($table, $entryID) {
  	$sql = "DELETE FROM {p} WHERE ID={p}";
  	$qry = $this -> DB_LINK -> query($sql, array($table, $entryID));
		return $entryID;
  }


	public function getJahresprogramm() {
		if(!isset($this -> jpArr)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/settings/jpArr.php');		
      $this -> jpArr = $jpArr;
    }
    return $this -> jpArr;
	}


	public function getLaender() {
		if(!isset($this -> laenderArr)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/settings/laenderArr.php');		
      $this -> laenderArr = $laenderArr;
    }
    return $this -> laenderArr;
	}


	public function getSprachen() {
		if(!isset($this -> sprachenArr)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/settings/sprachenArr.php');		
      $this -> sprachenArr = $sprachenArr;
    }
    return $this -> sprachenArr;
	}

	public function getWaehrungen() {
		if(!isset($this -> waehrungenArr)) {
      require_once($_SERVER['DOCUMENT_ROOT'].'/settings/waehrungenArr.php');		
      $this -> waehrungenArr = $waehrungenArr;
    }
    return $this -> waehrungenArr;
	}


	public function telMod($nummer, $land) {
		$laenderArr = $this -> getLaender();
		if(isset($laenderArr[$land])) {
			if($laenderArr[$land]['prenull'] == 0 && $nummer[0] == "0") {
				$nummer = substr($nummer, 1);
			}

 		  $vaz = $laenderArr[$this -> config['country']]['vaz']; if($vaz == "00") { $vaz = "+"; }
		  $vorwahl = "{$vaz}".$laenderArr[$land]['vorwahl'];
		  $nummer = "{$vorwahl} {$nummer}";
		}
		return $nummer;
	}

}
?>