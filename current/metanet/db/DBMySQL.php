<?php

/**
 * @author    METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2014, METANET AG
 */

namespace metanet\db;

use PDO;
use PDOException;
use PDOStatement;
use Exception;

class DBMySQL extends DB
{
	public function __construct(DBConnect $dbConnect)
	{
		$this->dbConnect = $dbConnect;

		try {
			parent::__construct(
				'mysql:host=' . $dbConnect->getHost() . ';dbname=' . $dbConnect->getDatabase() . ';charset=' . $dbConnect->getCharset()
				, $dbConnect->getUsername()
				, $dbConnect->getPassword()
			);

			$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			throw new Exception('PDO could not connect to the database ' . $dbConnect->getDatabase() . '@' . $dbConnect->getHost(), $e->getCode());
		}
	}

	public function prepareAndExecute(string $sql, array $parameters = []): PDOStatement
	{
		$statement = $this->prepare(query: $sql);
		$statement->execute(params: $parameters);

		return $statement;
	}

	public function prepare(string $query, array $options = []): PDOStatement|false
	{
		try {
			return parent::prepare($query, $options);
		} catch (PDOException $e) {
			throw new Exception('PDO could not prepare query: ' . $e->getMessage(), $e->getCode());
		}
	}

	public function select(PDOStatement $stmnt, array $params = []): false|array
	{
		try {
			$stmnt->execute($params);

			return $stmnt->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute select query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	public function selectAsObjects(PDOStatement $stmnt, $className, array $params = []): false|array
	{
		$paramCount = count($params);

		try {
			// Bind params to statement
			for ($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			return $stmnt->fetchAll(PDO::FETCH_CLASS, $className);
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute select query: ' . $e->getMessage(), $e->getCode());
		}
	}

	public function insert(PDOStatement $stmnt, array $params = []): false|int|string
	{
		$paramCount = count($params);

		try {
			// Bind params to statement
			for ($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			return $this->lastInsertId();
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute insert query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	public function update(PDOStatement $stmnt, array $params = []): int
	{
		$paramCount = count($params);

		try {
			// Bind params to statement
			for ($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			return $stmnt->rowCount();
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute update query: ' . $e->getMessage(), $e->errorInfo[1]);
		}
	}

	public function delete(PDOStatement $stmnt, array $params): int
	{
		$paramCount = count($params);

		try {
			// Bind params to statement
			for ($i = 0; $i < $paramCount; $i++) {
				$paramType = (is_int($params[$i])) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$stmnt->bindParam(($i + 1), $params[$i], $paramType);
			}

			$this->execute($stmnt);

			return $stmnt->rowCount();
		} catch (PDOException $e) {
			throw new Exception('PDO could not execute delete query: ' . $e->getMessage(), $e->getCode());
		}
	}

	public function getDbConnect(): DBConnect
	{
		return $this->dbConnect;
	}
}