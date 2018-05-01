<?php
/**
 * @author    METANET <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2014, METANET AG
 */

namespace metanet\db;

use \PDOStatement;

abstract class DBListener
{
	const QUERY_TYPE_DELETE = 'delete';
	const QUERY_TYPE_UPDATE = 'update';
	const QUERY_TYPE_INSERT = 'insert';

	/**
	 * Called on SELECT
	 *
	 * @param DB           $db
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 */
	public function onSelect(DB $db, PDOStatement $stmnt, array $params)
	{

	}

	/**
	 * Called on execute a statement
	 *
	 * @param DB           $db
	 * @param PDOStatement $stmnt
	 */
	public function onExecute(DB $db, PDOStatement $stmnt)
	{

	}

	/**
	 * Called on preparing a statement
	 *
	 * @param DB           $db
	 * @param PDOStatement $stmnt
	 */
	public function onPrepare(DB $db, PDOStatement $stmnt)
	{

	}

	/**
	 * Called on connect to db
	 *
	 * @param DB        $db
	 * @param DBConnect $dbConnect
	 */
	public function onConnect(DB $db, DBConnect $dbConnect)
	{

	}

	/**
	 * @param DB           $db
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 * @param              $queryType
	 */
	public function beforeMutation(DB $db, PDOStatement $stmnt, array $params, $queryType)
	{

	}

	/**
	 * @param DB           $db
	 * @param PDOStatement $stmnt
	 * @param array        $params
	 * @param              $queryType
	 */
	public function afterMutation(DB $db, PDOStatement $stmnt, array $params, $queryType)
	{

	}

	/**
	 * Called before a transaction starts
	 *
	 * @param DB $db
	 */
	public function beforeBeginTransaction(DB $db)
	{

	}

	/**
	 * Called after a transaction is commited
	 *
	 * @param DB $db
	 */
	public function afterCommit(DB $db)
	{

	}
}
/* EOF */ 