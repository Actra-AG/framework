<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 26.05.2009	DM	created

class DBStatement {

  private static $DB_LINK;
  private        $query;
  private        $preparedQuery;
  private        $params;
  private        $insertID; // DO NOT SET BY YOURSELF!
  private        $result; // DO NOT SET BY YOURSELF!
  private        $affectedRows; // DO NOT SET BY YOURSELF!

  public function __construct($DB_LINK = NULL, $query = '') {
    self::$DB_LINK = ($DB_LINK!=NULL&&!empty($DB_LINK))?$DB_LINK:Registry::get('DB');
    if (self::$DB_LINK===NULL)
      Registry::get('EXCEPTION_HANDLER')->throw_error('NO_DATABASE_CONNECTION');
    $this->query = $query;
    return $this;
  }

  public function execute() {
    self::$DB_LINK->execute($this);
        /*if (strtolower(substr($this->query, 0, 6))=="insert") {
			$this->db_insert_id = self::$DB_LINK->insert_id();
		}*/
    return $this;
  }

  public function setQuery($query) {
    $this->query = $query;
    return $this;
  }

  public function getQuery() {
    return $this->query;
  }

  public function bindParams(array $params = array()) {
    if (is_array($params)) {
      $query = $this->query;
      if (count($params)!=preg_match_all("/{p}/", $query, $matches) && ( strtolower(substr($query, 0, 6))=="insert" || strtolower(substr($query, 0, 6))=="update" || strtolower(substr($query, 0, 6))=="delete" || preg_match("/where/", strtolower($query)))) {
        echo ("no or not enough params found in statement: ".$query."<br />params (".count($params)." instead of ".preg_match_all("/{p}/", $query, $matches)."):");
        print_r($params);
        die();
      }
      $this->params = $params;
      $this->prepare_query();
      return $this;
    } else return false;
  }

  private function prepare_query() {
    $query = $this->query;
    foreach ($this->params as $replace) {
      $query = preg_replace("/{p}/", self::$DB_LINK->clean_input_var($replace, 'E'), $query, 1);
    }
    $this->preparedQuery = $query;
  }

  public function getPreparedStatement() {
    return $this->preparedQuery;
  }

  public function getResult() {
    return $this->result;
  }

  function fetch_object() {
    return self::$DB_LINK->fetch_object($this);
  }

  function fetch_row() {
    return self::$DB_LINK->fetch_row($this);
  }

  function fetch_assoc() {
    return self::$DB_LINK->fetch_assoc($this);
  }

  function num_rows() {
    return self::$DB_LINK->num_rows($this);
  }

  function affected_rows() {
    return $this->affectedRows;
  }

  public function getInsertId() {
    return $this->insertID;
  }

    /* ********************************************************************
     * DO NOT USE THE FOLLOWING FUNCTIONS!!!! JUST USED BY THE DB-CLASSES *
     ******************************************************************** */

  /**
   * DO NOT USE !!!! JUST USED BY THE DB-CLASSES
   */
  public function setInsertId($insertID) {
    $this->insertID = $insertID;
  }

  /**
   * DO NOT USE !!!! JUST USED BY THE DB-CLASSES
   */
  public function setAffectedRows($affectedRows) {
    $this->affectedRows = $affectedRows;
  }

  /**
   * DO NOT USE !!!! JUST USED BY THE DB-CLASSES
   */
  public function setResult($result) {
    $this->result = $result;
  }
}
?>