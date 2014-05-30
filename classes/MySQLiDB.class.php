<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 04.05.2009	DM	created

class MySQLiDB implements DB {
  private $db_host 		= NULL;
  private $db_user 		= NULL;
  private $db_pwd 		= NULL;
  private $db_database 	= NULL;
  private $db_link		= NULL;
  static private $instance= NULL;

  private function connect($host, $user, $pwd, $database) {
    $this->db_host		= $host;
    $this->db_user		= $user;
    $this->db_pwd		= $pwd;
    $this->db_database	= $database;
    $this->db_link = @mysqli_connect($this->db_host, $this->db_user, $this->db_pwd);
    if ($this->db_link == NULL) {
      Registry::get('EXCEPTION_HANDLER')->throw_error('WRONG_DATABASE_CONNECTION_PARAMETERS');
      die();
    }
    if (!@mysqli_select_db($this->db_link, $this->db_database)) {
      Registry::get('EXCEPTION_HANDLER')->throw_error('COULD_NOT_SELECT_DATABASE');
    }
  }

  static public function getInstance() {
    $config = Registry::get('CONFIG');
    if (self::$instance==NULL) {
      self::$instance = new MySQLiDB;
      self::$instance->connect($config['DB']['hostname'], $config['DB']['username'], $config['DB']['password'], $config['DB']['database']);
    }
    return self::$instance;
  }

  function execute(DBStatement $statement) {
    $config = Registry::get('CONFIG');
    $query = $statement->getPreparedStatement();
    $result = mysqli_query($this->db_link, $query);

    if (strtolower(substr($query, 0, 6))=="insert") {
      $statement->setInsertId(mysqli_insert_id($this->db_link));
    }
    if ($result == NULL) {
      if ($config['DB']['DEBUG']['enabled']==='true')
        $errormsg = mysqli_error($this->db_link);
      else
        $errormsg = '';
      $this->throw_error('COULD_NOT_PERFORM_QUERY', mysqli_error($this->db_link).' in query: '.$statement->getPreparedStatement());
      exit;
      return false;
    }
    $statement->setResult($result);
    $statement->setAffectedRows(mysqli_affected_rows($this->db_link));
    return $statement;
  }

  function query($query, $params = array()) {
    $statement = new DBStatement($this);
    $statement->setQuery($query);
    $statement->bindParams($params);
    $this->execute($statement);
    return $statement;
  }

  function fetch_object(DBStatement $statement) {
    $result = $statement->getResult();
    if ($result!=NULL) {
      return @mysqli_fetch_object($result);
    } else {
      $this->throw_error('NO_DATABASE_RESULT');
    }
    return false;
  }

  function fetch_row(DBStatement $statement) {
    $result = $statement->getResult();
    if ($result!=NULL) {
      return @mysqli_fetch_row($result);
    } else {
      $this->throw_error('NO_DATABASE_RESULT');
    }
    return false;
  }

  function fetch_assoc(DBStatement $statement) {
    $result = $statement->getResult();
    if ($result!=NULL) {
      return @mysqli_fetch_assoc($result);
    } else {
      $this->throw_error('NO_DATABASE_RESULT');
    }
    return false;
  }

  function num_rows(DBStatement $statement) {
    $result = $statement->getResult();
    if ($result!=NULL) {
      return @mysqli_num_rows($result);
    } else {
      $this->throw_error('NO_DATABASE_RESULT');
    }
    return false;
  }

  function throw_error($error, $more='') {
  //Registry::get('EXCEPTION_HANDLER')->throw_error('NO_DATABASE_CONNECTION');
    Registry::get('EXCEPTION_HANDLER')->throw_error($error, $more);
  }

  function clean_output_var($string, $mode='STUH') {
    if (strchr($mode, 'S')) $string = stripslashes($string);
    if (strchr($mode, 'T')) $string = strip_tags($string);
    if (strchr($mode, 'U')) $string = utf8_decode($string);
    if (strchr($mode, 'H')) $string = htmlentities($string);
    return $string;
  }

  function clean_input_var($string, $mode='TUE') {
    if (strchr($mode, 'T')) $string = strip_tags($string);
    if (strchr($mode, 'U')) $string = utf8_encode($string);
    if (get_magic_quotes_gpc()==true && strchr($mode, 'E')) $string = stripslashes($string);
    if (strchr($mode, 'E')) $string = mysqli_real_escape_string($this->db_link, $string);
    return $string;
  }

  function __destruct() {
    if ($this->db_link!=NULL) {
      @mysqli_close($this->db_link);
    }
  }
}
?>