<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\table;

use framework\db\DbQuery;
use framework\table\column\CallbackColumn;
use framework\table\column\DateColumn;
use framework\table\column\DefaultColumn;
use framework\table\TableItemModel;
use site\libs\auth\form\TokenSearchForm;
use site\libs\db\DB;
use site\libs\table\AbstractTable;
use site\settings\AuthTokenTypeEnum;

class TokenTable extends AbstractTable
{
    public function __construct(
        string $identifier,
        ?int $filterUserID,
        TokenSearchForm $tokenSearchForm
    ) {
        $dbQuery = DbQuery::createFromSqlQuery(
            query: '
				SELECT auth_token.userID,
				       auth_token.registered,
				       auth_token.registeredClient,
				       auth_token.type,
				       auth_token.claimed,
				       auth_token.claimedClient,
				       auth_token.token
				FROM auth_token
				    INNER JOIN auth_user ON auth_user.ID=auth_token.userID
				'
        );
        if (!is_null(value: $filterUserID)) {
            $dbQuery->addWherePart(
                wherePart: 'auth_token.userID=?',
                parameters: [
                    $filterUserID,
                ]
            );
        }
        $authTokenTypeEnum = $tokenSearchForm->authTokenTypeEnum;
        if (!is_null(value: $authTokenTypeEnum)) {
            $dbQuery->addWherePart(
                wherePart: 'auth_token.type=?',
                parameters: [
                    $authTokenTypeEnum->value,
                ]
            );
        }
        $searchQuery = $tokenSearchForm->searchQuery;
        if ($searchQuery !== '') {
            $dbQuery->addWherePart(
                wherePart: $tokenSearchForm->searchHelper->getBooleanQuery(
                    spaceSeparatedFieldNames: 'auth_user.firstName auth_user.lastName auth_token.token auth_token.registeredClient auth_token.claimedClient',
                    query_text: $searchQuery
                ),
                parameters: []
            );
        }
        parent::__construct(
            identifier: $identifier,
            db: DB::getHAAS(),
            dbQuery: $dbQuery,
            itemsPerPage: 100
        );
        $this->addColumn(
            abstractTableColumn: new DateColumn(
                identifier: 'registered',
                label: 'Erstellt (Datum)',
                isSortable: true,
                sortAscendingByDefault: false
            ),
            isDefaultSortColumn: true
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'registeredClient',
                label: 'Erstellt (Client)',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    $registeredClient = $tableItemModel->getRawValue(name: 'registeredClient');
                    if ($registeredClient === '') {
                        return '';
                    }
                    $list = [];
                    foreach (
                        get_object_vars(
                            object: json_decode(
                                json: $tableItemModel->getRawValue(
                                    name: 'registeredClient'
                                )
                            )
                        ) as $key => $val
                    ) {
                        $list[] = $key . ': ' . $val;
                    }
                    return implode(
                        separator: '<br>',
                        array: $list
                    );
                },
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'type',
                label: 'Typ',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    return AuthTokenTypeEnum::from(value: $tableItemModel->getRawValue(name: 'type'))->render();
                },
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new DateColumn(
                identifier: 'claimed',
                label: 'Eingelöst (Datum)',
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new CallbackColumn(
                identifier: 'claimedClient',
                label: 'Eingelöst (Client)',
                callbackFunction: function (TableItemModel $tableItemModel) {
                    $claimedClient = $tableItemModel->getRawValue(name: 'claimedClient');
                    if (is_null(value: $claimedClient)) {
                        return '';
                    }
                    $list = [];
                    foreach (
                        get_object_vars(
                            object: json_decode(
                                json: $claimedClient
                            )
                        ) as $key => $val
                    ) {
                        $list[] = $key . ': ' . $val;
                    }
                    return implode(
                        separator: '<br>',
                        array: $list
                    );
                },
                isSortable: true
            )
        );
        $this->addColumn(
            abstractTableColumn: new DefaultColumn(
                identifier: 'token',
                label: 'Token',
                isSortable: true
            )
        );
    }
}