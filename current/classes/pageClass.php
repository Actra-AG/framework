<?php

namespace classes;

use metanet\db\DBMySQL;

abstract class pageClass
{
	/** @var DBMySQL */
	protected $db;
	/** @var RequestHandler */
	protected $requestHandler;
	/** @var ShowPage */
	protected $showPage;
	protected $placeholders = [];

	public function __construct(DBMySQL $db, RequestHandler $requestHandler, ShowPage $showPage)
	{
		$this->db = $db;
		$this->requestHandler = $requestHandler;
		$this->showPage = $showPage;
	}

	abstract public function execute();

	public function getPlaceholders()
	{
		return $this->placeholders;
	}
}
/* EOF */