<?php
$reqArr = explode("/", $requestHandler->config['reqURI']);

if(!isset($reqArr[3])) { ErrorHandler::display_error(404); }

$dnArr = explode("?", $reqArr[3]);
$filename = urldecode($dnArr[0]);

$eventID = $reqArr[2];

$sql = "
SELECT
	ID
	, datumVon
	, datumBis
	, zeitVon
	, zeitBis
	, zeit
	, titel
	, ort
	, bemerkungen

FROM
	jahresprogramm

WHERE
	ID='{p}' AND export=1
";
$qry = $DB_LINK->query($sql, array($eventID));
if($qry->num_rows() != 1) {
    ErrorHandler::display_error(404);
}
$res = $qry->fetch_assoc();

if($res['datumBis'] == '0000-00-00') {
    $res['datumBis'] = $res['datumVon'];
}

$vArr = explode('-', $res['datumVon']);
$bArr = explode('-', $res['datumBis']);

$tvArr = explode(':', $res['zeitVon']);
$tbArr = explode(':', $res['zeitBis']);

$tsVon = mktime($tvArr[0], $tvArr[1], $tvArr[2], $vArr[1], $vArr[2], $vArr[0]);
$tsBis = mktime($tbArr[0], $tbArr[1], $tbArr[2], $bArr[1], $bArr[2], $bArr[0]);

//var_dump($vArr);
//var_dump($tvArr);
//echo date("d.m.Y H:i:s", $tsVon);

//exit;
$data = array(
    'filename' => $filename
, 'prodID' => '//' . $_SERVER['SERVER_NAME'] . '/calendar'
, 'calscale' => 'GREGORIAN'
, 'UID' => uniqid()
, 'address' => addslashes($res['ort'])
, 'summary' => addslashes($res['titel'])
, 'description' => addslashes($res['bemerkungen'])
, 'start' => dateToCal($tsVon)
, 'end' => dateToCal($tsBis)
, 'stamp' => dateToCal(time())
, 'URI' => 'URI:http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']
);

//var_dump($data); exit;

function dateToCal($time) {
    return date('Ymd\THis', $time); //  . 'Z'
}

// Build the ics file
$ical = <<<EOD
BEGIN:VCALENDAR
VERSION:2.0
PRODID:{$data['prodID']}
METHOD:PUBLISH
CALSCALE:{$data['calscale']}
BEGIN:VEVENT
UID:{$data['UID']}
LOCATION:{$data['address']}
SUMMARY:{$data['summary']}
DESCRIPTION:{$data['description']}
CLASS:PUBLIC
DTSTART:{$data['start']}
DTEND:{$data['end']}
DTSTAMP:{$data['stamp']}
URL;VALUE={$data['URI']}
END:VEVENT
END:VCALENDAR
EOD;

//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $data['filename']);
echo utf8_encode($ical);
exit;




/*
  $tz = "Europe/Zurich";
  $config = array(
  "unique_id" => 'ms-buelach.ch'
  , 'TZID' => $tz
  , 'format' => 'xcal'
  );

  // create a new calendar instance
  $v = new vcalendar($config);
  //var_dump($v); exit;

  // required of some calendar software
  $v->setProperty('method', 'PUBLISH');

  // required of some calendar software
  $v->setProperty('x-wr-calname', 'ms-buelach.ch Kalender');

  // required of some calendar software
  $v->setProperty('X-WR-CALDESC', 'ms-buelach.ch Kalendereintrag');

  // required of some calendar software
  $v->setProperty('X-WR-TIMEZONE', $tz);

  // required of some calendar software
  $xprops = array(
  "X-LIC-LOCATION" => $tz
  );

  // create timezone component(-s) opt. 1
  // based on present date
  iCalUtilityFunctions::createTimezone($v, $tz, $xprops);

  // create an event calendar component
  $vevent = & $v->newComponent("vevent");

  $start = array("year" => 2007, "month" => 4, "day" => 1, "hour" => 19, "min" => 0, "sec" => 0);
  $vevent->setProperty("dtstart", $start);

  $end = array("year" => 2007, "month" => 4, "day" => 1, "hour" => 22, "min" => 30, "sec" => 0);
  $vevent->setProperty("dtend", $end);

  // property name - case independent
  $vevent->setProperty("LOCATION", "Central Placa");

  $vevent->setProperty("summary", "PHP summit");
  $vevent->setProperty("description", "This is a description");
  $vevent->setProperty("comment", "This is a comment");
  $vevent->setProperty("attendee", "attendee1@icaldomain.net");

  // create an event alarm
  $valarm = & $vevent->newComponent("valarm");

  $valarm->setProperty("action", "DISPLAY");

  // reuse the event description
  $valarm->setProperty("description", $vevent->getProperty("description"));
  $d = sprintf("%04d%02d%02d %02d%02d%02d", 2007, 3, 31, 15, 0, 0);
  iCalUtilityFunctions::transformDateTime($d, $tz, "UTC", "Ymd\THis\Z");

  // create alarm trigger (in UTC datetime)
  $valarm->setProperty("trigger", $d);

  // create next event calendar component
  $vevent = & $v->newComponent("vevent");

  // alt. date format, now for an all-day event
  $vevent->setProperty("dtstart", "20070401", array("VALUE" => "DATE"));

  $vevent->setProperty("organizer", "boss@icaldomain.com");
  $vevent->setProperty("summary", "ALL-DAY event");
  $vevent->setProperty("description", "A description for an all-day event");
  $vevent->setProperty("resources", "COMPUTER PROJECTOR");
  // weekly, four occasions
  $vevent->setProperty("rrule", array("FREQ" => "WEEKLY", "count" => 4));

  // supporting parse of strict rfc2445 formatted text
  // all calendar components are described in rfc2445
  // a complete iCalcreator function list (ex. setProperty) in iCalcreator manual
  $vevent->parse("LOCATION:1CP Conference Room 4350");

  // create timezone component(-s) opt. 2
  // based on all start dates in events (i.e. dtstart)
  iCalUtilityFunctions::createTimezone($v, $tz, $xprops);

  $v->returnCalendar();

  //header( 'Content-Type: application/calendar+xml; charset=utf-8' );
  //$str = iCal2XML($v);
  //echo $str;
 */
?>