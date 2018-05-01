<?php

/**
 * @author    METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2014, METANET AG
 */

namespace metanet\db;

use \PDO;
use \PDOStatement;
use \PDOException;
use \ArrayObject;
use Exception;

abstract class DB extends PDO
{
	const TYPE_MYSQL = 'mysql';
	const TYPE_POSTGRESQL = 2;
	const TYPE_MSSQL = 3;
	protected $listeners;
	protected $muteListeners;
	protected $transactionName;
	protected $dbConnect;

	public function __construct($dsn, $username = null, $passwd = null, $options = null)
	{
		parent::__construct($dsn, $username, $passwd, $options);

		$this->listeners = new ArrayObject();
		$this->muteListeners = false;
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

			$this->triggerListeners('onExecute', [$this, $stmnt]);
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute query: ' . $e->getMessage() . ' ' . $e->errorInfo[1] . ' ' . $stmnt->queryString);
		}
	}

	public function beginTransaction($transactionName = null)
	{
		$this->transactionName = $transactionName;

		try {
			$this->triggerListeners('beforeBeginTransaction', [$this]);

			parent::beginTransaction();
		} catch (PDOException $e) {
			throw new Exception('PDO could not begin transaction: ' . $e->getMessage() . ' ' . $e->getCode());
		}
	}

	/**
	 * @throws Exception
	 */
	public function commit()
	{
		try {
			parent::commit();

			$this->triggerListeners('afterCommit', [$this]);

			$this->transactionName = null;
		} catch (PDOException $e) {
			throw new Exception('PDO could not commit transaction: ' . $e->getMessage() . ' ' . $e->getCode());
		}
	}

	/**
	 * Adds a DBListener to listen on some events of the DB class
	 *
	 * @param DBListener $listener The listener object to register
	 * @param string     $name     The name of the listener [optional]
	 */
	public function addListener(DBListener $listener, $name = null)
	{
		if ($name !== null) {
			$this->listeners->offsetSet($name, $listener);
		} else {
			$this->listeners->append($listener);
		}
	}

	/**
	 * Removes the listener
	 *
	 * @param string $name The name of the listener which should be removed
	 */
	public function removeListener($name)
	{
		$this->listeners->offsetUnset($name);
	}

	/**
	 * Removes all registered listeners at once
	 */
	public function removeAllListeners()
	{
		$this->listeners = new ArrayObject();
	}

	/**
	 * Returns the name of the current transaction or null if none given
	 *
	 * @return string|null
	 */
	public function getTransactionName()
	{
		return $this->transactionName;
	}

	/**
	 * Sets the listeners to mute so they'll be not triggered until mute is set to false again
	 *
	 * @param boolean $mute Mute = true, unmute = false
	 */
	public function setListenersMute($mute)
	{
		$this->muteListeners = $mute;
	}

	/**
	 * Returns the state of the listeners if they're mute or not
	 *
	 * @return bool The mute state of the listeners
	 */
	public function areListenersMute()
	{
		return $this->muteListeners;
	}

	/**
	 * Returns all the current registered listeners
	 *
	 * @return ArrayObject List of registered listeners
	 */
	public function getListeners()
	{
		return $this->listeners;
	}

	/**
	 * Triggers a call of a specific method from all registered listener classes if the listeners are not set to mute
	 *
	 * @param string $method listener method that should be called
	 * @param array  $params The parameters for the listener method
	 */
	protected function triggerListeners($method, array $params = [])
	{
		if ($this->muteListeners === true) {
			return;
		}

		// Mute all the listeners cause we don't want listeners called in listeners
		// If we do so: unmute the listeners in the listener method itself
		$this->muteListeners = true;

		foreach ($this->listeners as $l) {
			/** @var DBListener $l */
			call_user_func_array([$l, $method], $params);
		}

		// Unmute listeners cause from now on we're not in a listener method anymore
		$this->muteListeners = false;
	}

	/**
	 * Creates a string like "?,?,?,..." for the number of array entries given
	 *
	 * @param $paramArr
	 *
	 * @return string
	 */
	public static function createInQuery($paramArr)
	{
		return implode(',', array_fill(0, count($paramArr), '?'));
	}
}

/* EOF */