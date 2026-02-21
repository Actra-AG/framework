<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace site\libs\db;

use DateTimeImmutable;

class DbNewsRepository {
    public static function listForStartPage(): DbNewsCollection {
        $dbNewsCollection = new DbNewsCollection();
        foreach(DB::get()->select(
            sql: '
                SELECT ID,
                       registered_by,
                       datum,
                       titel,
                       teaser,
                       text,
                       archiv,
                       typ
                FROM news
                WHERE archiv=0
                  AND typ=1
                ORDER BY datum DESC
            '
        ) as $item) {
            $dbNewsCollection->add(dbNews: new DbNews(
                ID: $item->ID,
                registeredByUserID: $item->registered_by,
                date: new DateTimeImmutable(datetime: $item->datum),
                title: $item->titel,
                teaser: $item->teaser,
                htmlContent: $item->text,
                isArchive: $item->archiv,
                type: $item->typ
            ));
        }

        return $dbNewsCollection;
    }
}