<?php
namespace classes;

use metanet\db\DBMySQL;

abstract class serviceClass
{
	/** @var DBMySQL */
	protected $db;
	/** @var RequestHandler */
	protected $requestHandler;

	public function __construct(DBMySQL $db, RequestHandler $requestHandler)
	{
		$this->db = $db;
		$this->requestHandler = $requestHandler;
	}

	abstract public function execute();
}
/* EOF */