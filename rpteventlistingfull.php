<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Full Event Listing</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$type = isset($_REQUEST['Type']) ? $_REQUEST['Type'] : "";

echo '<div class="container"><h1>Event Listing</h1>';


echo '
  <p>This report produces a complete list of ALL events not marked &apos;DELETE&apos; and all fields for each in a CSV format file to download and open with a spreadsheet program.</p>
';

// create report
echo '
<a class="hidden-print" href="downloads/eventlistingfull.csv">DOWN LOAD RESULTS</a><span title="Download file with quoted values and comma separated fields" class="hidden-print glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px;"></span>';

$sql = '
SELECT * FROM `events` 
WHERE 1=1
  AND `TripStatus` NOT LIKE "Delete"
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<br>row count: '.$rc.'<br>';
// Fields
// Day	Trip	TripStatus	StartTime	EndTime	Type	Event	TypeOfEvent	Level	Venue	VenueInst	Leader1	Leader2	Leader3	Leader4	FeeRequired	FEE	TransportNeeded	Transportation	MaxAttendees	SecondaryStatus	MultiEvent	MultiEventCode	Program

$csvmask = '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s"'."\n";

$csv = 'Day,Trip,TripStatus,StartTime,EndTime,Type,Event,TypeOfEvent,Level,EventVenue,EvtVenInst,MeetingVenue,MtngVenInst,Leader1,Leader2,Leader3,Leader4,FeeRequired,FEE,TransportNeeded,Transportation,MaxAttendees,ProductionNotes,MultiEvent,MultiEventCode,Program'."\n";

while ($r = $res->fetch_assoc()) {
  $st = date("g:i A", strtotime($r['StartTime']));
  $et = date("g:i A", strtotime($r['EndTime']));
  $csv .= sprintf($csvmask,$r['Day'],$r['Trip'],$r['TripStatus'],$r['StartTime'],$r['EndTime'],$r['Type'],$r['Event'],$r['TypeOfEvent'],$r['Level'],$r['Site'],$r['SiteRoom'], 
$r['Site2'], $r['Site2Room'], $r['Leader1'],$r['Leader2'],$r['Leader3'],$r['Leader4'],$r['FeeRequired'],$r['FEE'],$r['TransportNeeded'],$r['Transportation'],$r['MaxAttendees'],$r['SecondaryStatus'],$r['MultiEvent'],$r['MultiEventCode'],$r['Program']);
  
//  echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  }

// echo "<pre> csv \n"; print_r($csv); echo '</pre>';
file_put_contents("downloads/eventlistingfull.csv", $csv);

?>
</div>  <!-- container -->
</body>
</html>