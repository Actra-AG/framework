<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\table\renderer;

use framework\pagination\Pagination;
use framework\table\table\DbResultTable;

readonly class TablePaginationRenderer
{
	public function __construct(public ?string $individualHtmlSnippetPath = null) { }

	public function render(DbResultTable $dbResultTable, int $entriesPerPage = 25, int $beforeAfter = 2, int $startEnd = 1): string
	{
		return Pagination::render(
			listIdentifier: $dbResultTable->identifier,
			totalAmount: $dbResultTable->getTotalAmount(),
			currentPage: $dbResultTable->getCurrentPaginationPage(),
			entriesPerPage: $entriesPerPage,
			beforeAfter: $beforeAfter,
			startEnd: $startEnd,
			additionalLinkParameters: $dbResultTable->getAdditionalLinkParameters(),
			individualHtmlSnippetPath: $this->individualHtmlSnippetPath
		);
	}
}