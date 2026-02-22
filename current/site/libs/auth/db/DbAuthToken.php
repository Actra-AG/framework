<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\auth\db;

use framework\common\StringUtils;
use framework\core\HttpRequest;
use framework\session\AbstractSessionHandler;
use site\libs\db\DB;
use site\settings\AuthTokenTypeEnum;

class DbAuthToken
{
    public static function createToken(
        DbAuthUserItem $dbAuthUserItem,
        AuthTokenTypeEnum $authTokenType
    ): string {
        $token = strtoupper(
            string: StringUtils::randomString(
                requiredStringLength: 6,
                noSpecialChars: true
            )
        );
        DB::getHAAS()->execute(
            sql: '
                INSERT into auth_token
                SET auth_token.userID=?,
                    auth_token.type=?,
                    auth_token.token=?,
                    auth_token.registeredClient=?
            ',
            parameters: [
                $dbAuthUserItem->ID,
                $authTokenType->value,
                $token,
                DbAuthToken::getClientData(),
            ]
        );

        return $token;
    }

    private static function getClientData(): string
    {
        return json_encode(value: [
            'userAgent' => HttpRequest::getUserAgent(),
            'ipAddress' => HttpRequest::getRemoteAddress(),
            'sessionId' => AbstractSessionHandler::getSessionHandler()->getID(),
        ]);
    }

    public static function getClaimable(
        AuthTokenTypeEnum $authTokenType,
        string $token
    ): ?DbAuthTokenItem {
        $res = DB::getHAAS()->select(
            sql: '
				SELECT auth_token.ID,
				       auth_token.userID,
				       auth_user.email
				FROM auth_token
				    INNER JOIN auth_user ON auth_token.userID = auth_user.ID
				WHERE auth_token.type=?
				  AND auth_token.token=?
				  AND auth_token.registered>=DATE_SUB(NOW(), INTERVAL ? MINUTE)
				  AND auth_token.claimed IS NULL
				  AND auth_token.token=(SELECT last.token
				                        FROM auth_token last
				                        WHERE last.userID=auth_token.userID
				                          AND last.claimed IS NULL
				                        ORDER BY last.registered
				                        DESC LIMIT 1
				  )
			',
            parameters: [
                $authTokenType->value,
                $token,
                $authTokenType->getExpirationInMinutes(),
            ]
        );
        if ((count(value: $res) !== 1)) {
            return null;
        }
        $data = $res[0];

        return new DbAuthTokenItem(
            ID: $data->ID,
            userID: $data->userID,
            email: $data->email
        );
    }

    public static function claim(DbAuthTokenItem $dbAuthTokenItem): void
    {
        DB::getHAAS()->execute(
            sql: '
                UPDATE auth_token
                SET auth_token.claimed=NOW(),
                    auth_token.claimedClient=?
                WHERE auth_token.ID=?
            ',
            parameters: [
                DbAuthToken::getClientData(),
                $dbAuthTokenItem->ID,
            ]
        );
    }

    public static function deleteByUserID(int $userID): void
    {
        DB::getHAAS()->execute(
            sql: '
                DELETE FROM auth_token
                       WHERE userID=?
            ',
            parameters: [
                $userID,
            ]
        );
    }
}