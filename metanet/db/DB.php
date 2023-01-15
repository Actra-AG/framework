<?php

/**
 * @author    METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2014, METANET AG
 */

namespace metanet\db;

use PDO;
use PDOStatement;
use PDOException;
use ArrayObject;
use Exception;

abstract class DB extends PDO
{
	protected $listeners;
	protected $transactionName;
	protected $dbConnect;

	public function __construct($dsn, $username = null, $passwd = null, $options = null)
	{
		parent::__construct($dsn, $username, $passwd, $options);

		$this->listeners = new ArrayObject();
		$this->transactionName = null;
	}

	/**
	 * Returns the result as an array of $className objects
	 *
	 * @param PDOStatement $stmnt     The prepared statement
	 * @param array        $params    The parameters for the prepared statement
	 * @param String       $className The mapped class name
	 *
	 * @return array
	 */
	abstract public function selectAsObjects(PDOStatement $stmnt, $className, array $params = []);

	/**
	 * Returns the result as an array of anonymous objects
	 *
	 * @param PDOStatement $stmnt  The prepared statement
	 * @param array        $params The parameters for the prepared statement
	 *
	 * @return array
	 */
	abstract public function select(PDOStatement $stmnt, array $params = []);

	/**
	 * Inserts a prepared statement with the given parameters
	 *
	 * @param PDOStatement $stmnt  The prepared statement
	 * @param array        $params The paremeters for the prepared statement
	 *
	 * @return int ID of inserted row
	 */
	abstract public function insert(PDOStatement $stmnt, array $params = []);

	/**
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 *
	 * @return int Affected rows
	 */
	abstract public function update(PDOStatement $stmnt, array $params);

	/**
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 *
	 * @return int Affected rows
	 */
	abstract public function delete(PDOStatement $stmnt, array $params);

	/**
	 * Returns the DBConnect object with the current used connection
	 *
	 * @return DBConnect
	 */
	abstract public function getDbConnect();

	/**
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 *
	 * @throws Exception
	 */
	public function execute(PDOStatement $stmnt, $params = [])
	{
		try {
			$old = setlocale(LC_NUMERIC, null);
			setlocale(LC_NUMERIC, 'us_US');

			$stmnt->execute($params);

			setlocale(LC_NUMERIC, $old);
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute query: ' . $e->getMessage() . ' ' . $e->errorInfo[1] . ' ' . $stmnt->queryString);
		}
	}
}