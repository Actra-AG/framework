<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 22.07.2009	CM	updated
# 18.05.2009	DM	transfered into a class
# 19.08.2007	CM	created

class DBSessions {

  static private $DB_LINK = null;

  static public function connect() {
    self::$DB_LINK = Registry::get('DB');
    if (self::$DB_LINK===NULL)
      Registry::get('EXCEPTION_HANDLER')->throw_error('NO_DATABASE_CONNECTION');
  }

  static function open_session() {
    return true;
  }

  static function close_session() {
    return true;
  }

  static function read_session($sid) {
    $data = '';
    $expire = (int)ini_get("session.gc_maxlifetime");
    $qry = self::$DB_LINK->query("SELECT data FROM sessions WHERE ID='{p}' AND DATE_ADD(last_accessed, INTERVAL {p} SECOND) > NOW()", array($sid, $expire));
    if(1 == $qry->num_rows()) {
      $data = $qry->fetch_row();
      list($data) = $data;
    }
    return $data;
  }

  static function write_session($sid, $data) {
  	$ip = ''; if(isset($_SERVER['REMOTE_ADDR'])) { $ip = $_SERVER['REMOTE_ADDR']; }
    $qry = self::$DB_LINK->query("REPLACE INTO sessions (ID, data, ip) VALUES ('{p}', '{p}', '{p}')", array($sid, $data, $ip));
    return $qry->affected_rows();
  }

  static function destroy_session($sid) {
    $_SESSION = array();
    $qry = self::$DB_LINK->query("DELETE FROM sessions WHERE ID='{p}'", array($sid));
    return $qry->affected_rows();
  }

  static function clean_session($expire) {
    $expire = (int)$expire;
    $qry = self::$DB_LINK->query("DELETE FROM sessions WHERE DATE_ADD(last_accessed, INTERVAL {p} SECOND) < NOW()", array($expire));
    return $qry->affected_rows();
  }
}
?>