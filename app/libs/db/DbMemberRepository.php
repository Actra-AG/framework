<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\db;

use actra\backend\libs\db\DB;
use actra\backend\libs\db\DbAuthUser;
use actra\yuf\auth\AccessRightCollection;
use actra\yuf\db\DbQuery;
use DateTimeImmutable;
use stdClass;

class DbMemberRepository
{
    public static function getDbQuery(): DbQuery
    {
        return DbQuery::createFromSqlQuery(
            query: '
                SELECT member.ID,
                       member.ehren AS honorary,
                       member.accepted,
                       member.denied,
                       member.anrede as gender,
                       member.lizenz as license,
                       member.strasse as street,
                       member.plz as zip,
                       member.ort as city,
                       member.telefon as phone,
                       member.kommentar as comment,
                       member.bemerkungen as notes,
                       member.geburtsdatum as birthdate,
                       member.ernannt as honored,
                       CONCAT_WS(\' \', auth_user.firstName, auth_user.lastName) AS fullName,
                       auth_user.ID,
                       auth_user.registered,
                       auth_user.invited,
                       (SELECT MAX(registered) FROM auth_login WHERE userID=auth_user.ID) AS lastLogin,
                       auth_user.email,
                       auth_user.active,
                       auth_user.firstName,
                       auth_user.lastName,
                       (SELECT GROUP_CONCAT(auth_group_right.rightName) FROM auth_group_right WHERE auth_group_right.groupID IN (SELECT groupID FROM auth_user_group WHERE userID=auth_user.ID)) AS accessRights,
                       vereine.name AS clubName
                FROM benutzer member
                    INNER JOIN auth_user ON member.ID=auth_user.ID
                    LEFT JOIN vereine ON member.vereinID=vereine.ID
            '
        );
    }

    private static function createItem(stdClass $data): DbMember
    {
        return new DbMember(
            dbAuthUser: new DbAuthUser(
                ID: $data->ID,
                registered: new DateTimeImmutable(datetime: $data->registered),
                invitedDate: is_null(value: $data->invited) ? null : new DateTimeImmutable(datetime: $data->invited),
                lastLogin: is_null(value: $data->lastLogin) ? null : new DateTimeImmutable(datetime: $data->lastLogin),
                email: $data->email,
                isActive: ($data->active === 1),
                accessRightCollection: AccessRightCollection::createFromStringArray(
                    input: explode(
                        separator: ',',
                        string: (string)$data->accessRights
                    )
                ),
                firstName: $data->firstName,
                lastName: $data->lastName
            ),
            clubName: (string)$data->clubName,
            gender: $data->gender,
            street: $data->street,
            zip: $data->zip,
            city: $data->city,
            license: $data->license,
            phone: $data->phone,
            birthDate: $data->birthdate,
            comment: $data->comment,
            notes: $data->notes,
            honorary: ($data->honorary === 1),
            honored: $data->honored
        );
    }

    public static function select(DbQuery $dbQuery): DbMemberCollection
    {
        $dbMemberCollection = new DbMemberCollection();
        foreach (
            $dbQuery->selectFromDb(
                db: DB::get(),
                offset: 0,
                rowCount: 1000
            ) as $item
        ) {
            $dbMemberCollection->add(
                dbMember: DbMemberRepository::createItem(data: $item)
            );
        }

        return $dbMemberCollection;
    }

    public static function selectByID(int $ID): ?DbMember
    {
        $dbQuery = DbMemberRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'member.ID=?',
            parameters: [$ID]
        );
        $dbMemberCollection = DbMemberRepository::select(dbQuery: $dbQuery);

        return $dbMemberCollection->isEmpty() ? null : $dbMemberCollection->first();
    }

    public static function listPendingRegistrationRequests(): DbMemberCollection
    {
        $dbQuery = DbMemberRepository::getDbQuery();
        $dbQuery->addWherePart(
            wherePart: 'member.accepted IS NULL AND member.denied IS NULL',
            parameters: []
        );
        $dbQuery->addOrderPart(
            column: 'auth_user.registered',
            ascending: false
        );
        return DbMemberRepository::select(dbQuery: $dbQuery);
    }

    public static function insert(
        int $ID,
        int $clubID,
        int $registeredBy,
        ?DateTimeImmutable $accepted,
        int $licence,
        string $gender,
        string $street,
        string $zip,
        string $city,
        string $phone,
        string $comment,
        string $notes,
        ?DateTimeImmutable $birthdate,
        bool $honorary,
        int $honored
    ): void {
        DB::get()->execute(
            sql: '
                INSERT INTO benutzer
                SET ID=?,
                    vereinID=?,
                    registered_by=?,
                    accepted=?,
                    lizenz=?,
                    anrede=?,
                    strasse=?,
                    plz=?,
                    ort=?,
                    telefon=?,
                    kommentar=?,
                    bemerkungen=?,
                    geburtsdatum=?,
                    ehren=?,
                    ernannt=?
            ',
            parameters: [
                $ID,
                $clubID,
                $registeredBy,
                $accepted?->format(format: 'Y-m-d H:i:s'),
                $licence,
                $gender,
                $street,
                $zip,
                $city,
                $phone,
                $comment,
                $notes,
                $birthdate?->format(format: 'Y-m-d'),
                $honorary,
                $honored
            ]
        );
    }
}