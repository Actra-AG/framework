<?php

namespace services;

use classes\serviceClass;
use classes\ErrorHandler;
use PDO;

class calendar extends serviceClass
{
	public function execute()
	{
		$reqArr = explode("/", $this->requestHandler->config['reqURI']);

		if (!isset($reqArr[3])) {
			ErrorHandler::display_error(404);
		}

		$dnArr = explode("?", $reqArr[3]);
		$filename = urldecode($dnArr[0]);

		$eventID = $reqArr[2];

		$sql = "
		SELECT
			ID,
			datumVon,
			datumBis,
			zeitVon,
			zeitBis,
			zeit,
			titel,
			ort,
			bemerkungen,
			vorstand

		FROM
			jahresprogramm

		WHERE
			ID=? AND export=1
		";
		$qry = $this->db->prepareAndExecute($sql, [$eventID]);
		if ($qry->rowCount() != 1) {
			ErrorHandler::display_error(404);
		}
		$res = $qry->fetch(PDO::FETCH_ASSOC);

		if ($res['datumBis'] == '0000-00-00') {
			$res['datumBis'] = $res['datumVon'];
		}

		$vArr = explode('-', $res['datumVon']);
		$bArr = explode('-', $res['datumBis']);

		$tvArr = explode(':', $res['zeitVon']);
		$tbArr = explode(':', $res['zeitBis']);

		$tsVon = mktime($tvArr[0], $tvArr[1], $tvArr[2], $vArr[1], $vArr[2], $vArr[0]);
		$tsBis = mktime($tbArr[0], $tbArr[1], $tbArr[2], $bArr[1], $bArr[2], $bArr[0]);

		$data = [
			'filename'      => $filename
			, 'prodID'      => '//' . $_SERVER['SERVER_NAME'] . '/calendar'
			, 'calscale'    => 'GREGORIAN'
			, 'UID'         => uniqid()
			, 'address'     => addslashes($res['ort'])
			, 'summary'     => addslashes($res['titel'])
			, 'description' => $res['vorstand'] == 1 ? '' : addslashes($res['bemerkungen'])
			, 'start'       => $this->dateToCal($tsVon)
			, 'end'         => $this->dateToCal($tsBis)
			, 'stamp'       => $this->dateToCal(time())
			, 'URI'         => 'URI:http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
		];

		// Build the ics file
		$ical = <<<EOD
BEGIN:VCALENDAR
VERSION:2.0
PRODID:{$data['prodID']}
METHOD:PUBLISH
CALSCALE:{$data['calscale']}
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
UID:{$data['UID']}
LOCATION:{$data['address']}
SUMMARY:{$data['summary']}
DESCRIPTION:{$data['description']}
CLASS:PUBLIC
DTSTART;TZID=Europe/Zurich:{$data['start']}
DTEND;TZID=Europe/Zurich:{$data['end']}
DTSTAMP:{$data['stamp']}
URL;VALUE={$data['URI']}
END:VEVENT
END:VCALENDAR
EOD;

		//set correct content-type-header
		header('Content-type: text/calendar; charset=utf-8');
		header('Content-Disposition: attachment; filename=' . $data['filename']);
		echo mb_convert_encoding(string: $ical, to_encoding: 'UTF-8');
		exit;
	}

	protected function dateToCal($time)
	{
		return date('Ymd\THis', $time); //  . 'Z'
	}

}