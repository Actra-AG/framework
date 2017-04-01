<?php

/**
 * @author METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2014, METANET AG
 */

namespace metanet\db;

use \PDO;
use \PDOException;
use \PDOStatement;
use Exception;

class DBMySQL extends DB {

	public function __construct(DBConnect $dbConnect) {
		$this->dbConnect = $dbConnect;

		try {
			parent::__construct(
				'mysql:host=' . $dbConnect->getHost() . ';dbname=' . $dbConnect->getDatabase() . ';charset=' . $dbConnect->getCharset()
				, $dbConnect->getUsername()
				, $dbConnect->getPassword()
			);

			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//			$this->query("SET NAMES '" . $dbConnect->getCharset() . "'");
//			$this->query("SET CHARSET '" . $dbConnect->getCharset() . "'");

			$this->triggerListeners('onConnect', array($this, $this->dbConnect));
		} catch(PDOException $e) {
			throw new Exception('PDO could not connect to the database ' . $dbConnect->getDatabase() . '@' . $dbConnect->getHost(), $e->getCode());
		}
	}
	
	public function query($sql, $params = array()) {
		$statement = $this->prepare($sql);
		$statement->execute($params);
		return $statement;
	}

	/**
	 * @param string $sql
	 * @param array $driver_options
	 * @return PDOStatement|void
	 * @throws Exception
	 */
	public function prepare($sql, $driver_options = array()) {
		try {
			$stmnt = parent::prepare($sql, $driver_options);

			$this->triggerListeners('onPrepare', array($this, $stmnt));

			return $stmnt;
		} catch(PDOException $e) {
			throw new Exception('PDO could not prepare query: ' . $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * @param PDOStatement $stmnt
	 * @param array $params
	 * @return array|void
	 * @throws Exception
	 */
	public function select(PDOStatement $stmnt, array $params = array()) {

		try {
			$stmnt->execute($params);
			$this->triggerListeners('onSelect', array($this, $stmnt, $params));
				
			return $stmnt->fetchAll(PDO::FETCH_OBJ);
		} catch(PDOException $e) {
			throw new Exception('PDO could not execute select query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	public function selectAsObjects(PDOStatement $stmnt, $className, array $params = array()) {
		$paramCount = count($params);

		try {
			// Bind params to statement
			for($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			$this->triggerListeners('onSelect', array($this, $stmnt, $params));

			return $stmnt->fetchAll(PDO::FETCH_CLASS, $className);
		} catch(PDOException $e) {
			throw new Exception('PDO could not execute select query: ' . $e->getMessage(), $e->getCode());
		}
	}

	/**
	 * @param PDOStatement $stmnt
	 * @param array $params
	 * @return int|void
	 * @throws Exception
	 */
	public function insert(PDOStatement $stmnt, array $params = array()) {
		$paramCount = count($params);

		try {
			$this->triggerListeners('beforeMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_INSERT));

			// Bind params to statement
			for($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			$this->triggerListeners('afterMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_INSERT));

			return $this->lastInsertId();
		} catch(PDOException $e) {
			throw new Exception('PDO could not execute insert query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	/**
	 * @param PDOStatement $stmnt
	 * @param array $params
	 * @return int|void
	 * @throws Exception
	 */
	public function update(PDOStatement $stmnt, array $params = array()) {
		$paramCount = count($params);

		try {
			$this->triggerListeners('beforeMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_UPDATE));

			// Bind params to statement
			for($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			$this->triggerListeners('afterMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_UPDATE));

			return $stmnt->rowCount();
		} catch(PDOException $e) {
			throw new Exception('PDO could not execute update query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	/**
	 * @param PDOStatement $stmnt
	 * @param array $params
	 * @return int|void
	 * @throws Exception
	 */
	public function delete(PDOStatement $stmnt, array $params) {
		$paramCount = count($params);

		try {
			$this->triggerListeners('beforeMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_DELETE));

			// Bind params to statement
			for($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			$this->triggerListeners('afterMutation', array($this, $stmnt, $params, DBListener::QUERY_TYPE_DELETE));

			return $stmnt->rowCount();
		} catch(PDOException $e) {
			throw new Exception('PDO could not execute delete query: ' . $e->getMessage(), $e->getCode());
		}
	}

	public function getDbConnect() {
		return $this->dbConnect;
	}
}

/* EOF */