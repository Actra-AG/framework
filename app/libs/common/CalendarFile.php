<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

namespace app\libs\common;

use app\libs\db\DbEvent;

class CalendarFile
{
    private readonly string $ical;

    public function __construct(
      DbEvent $dbEvent,
      private readonly string $filename
    ) {
        $prodID = '//' . $_SERVER['SERVER_NAME'] . '/calendar';
        $uniqid = uniqid();
        $address = addslashes(string: $dbEvent->location);
        $summary = addslashes(string: $dbEvent->title);
        $description = addslashes(string: $dbEvent->notes);
        $startDate = $this->dateToCal(time: $dbEvent->dateFrom->getTimestamp());
        $endDate = $this->dateToCal(time: $dbEvent->dateTo->getTimestamp());
        $stamp = $this->dateToCal(time: time());
        $uri = 'URI:http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $this->ical = <<<EOD
BEGIN:VCALENDAR
VERSION:2.0
PRODID:{$prodID}
METHOD:PUBLISH
CALSCALE:GREGORIAN
BEGIN:VTIMEZONE
TZID:Europe/Zurich
X-LIC-LOCATION:Europe/Zurich
BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT
BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE
BEGIN:VEVENT
UID:{$uniqid}
LOCATION:{$address}
SUMMARY:{$summary}
DESCRIPTION:{$description}
CLASS:PUBLIC
DTSTART;TZID=Europe/Zurich:{$startDate}
DTEND;TZID=Europe/Zurich:{$endDate}
DTSTAMP:{$stamp}
URL;VALUE={$uri}
END:VEVENT
END:VCALENDAR
EOD;
    }

    public function downloadAndExit(): void
    {
        header(header: 'Content-type: text/calendar; charset=utf-8');
        header(header: 'Content-Disposition: attachment; filename=' . $this->filename);
        echo mb_convert_encoding(
          string: $this->ical,
          to_encoding: 'UTF-8'
        );
        exit;
    }

    private function dateToCal(int $time): string
    {
        return date(format: 'Ymd\THis', timestamp: $time);
    }
}