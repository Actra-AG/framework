<?php
# ------------------------------
# Actra AG - http://www.actra.ch
# ------------------------------
# 22.07.2009	CM	created

namespace classes;

use metanet\db\DBMySQL;

class bsvb
{
	public array $jpArr;
	/** @var DBMySQL */
	private $DB_LINK;

	function __construct()
	{
		$this->DB_LINK = Registry::get('DB');
	}

	public function insertEntry($table, $fields = [])
	{
		if (count($fields) == 0) {
			return 0;
		}
		$fArr = [];
		$params = [];
		foreach ($fields as $key => $val) {
			$fArr[] = "{$key}=?";
			$params[] = $val;
		}
		$sql = "INSERT INTO {$table} SET " . implode(", ", $fArr);
		$this->DB_LINK->prepareAndExecute($sql, $params);

		return $this->DB_LINK->lastInsertId();
	}

	public function updateEntry($table, $entryID, $fields = [])
	{
		if (count($fields) == 0) {
			return 0;
		}
		$fArr = [];
		$params = [];
		foreach ($fields as $key => $val) {
			$fArr[] = "{$key}=?";
			$params[] = $val;
		}
		$params[] = $entryID;
		$sql = "UPDATE {$table} SET " . implode(", ", $fArr) . " WHERE ID=?";
		$qry = $this->DB_LINK->prepareAndExecute($sql, $params);

		return $qry->rowCount();
	}

	public function getJahresprogramm()
	{
		if (!isset($this->jpArr)) {
			$jpArr = [];
			require_once($_SERVER['DOCUMENT_ROOT'] . '/settings/jpArr.php');
			$this->jpArr = $jpArr;
		}

		return $this->jpArr;
	}
}