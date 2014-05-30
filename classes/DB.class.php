<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 04.05.2009	DM	created

interface DB {

  static public function getInstance();

  function query($query, $params = array());

  function fetch_object(DBStatement $statement);

  function fetch_row(DBStatement $statement);

  function num_rows(DBStatement $statement);

  function throw_error($error);

  function clean_output_var($string, $mode='STUH');

  function clean_input_var($string, $mode='TUE');

  function __destruct();
}
?>