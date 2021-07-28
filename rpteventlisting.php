<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Event Listing</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-sortable.js"></script>
<script>
$(function() {
  $.bootstrapSortable({ sign: 'AZ' })
});
</script>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

// Process listing based on selected criteria
$day = isset($_REQUEST['Day']) ? $_REQUEST['Day'] : "";

echo '<h3>Event Listing</h3>';

if ($day == '') {
  echo '
  <div class=container>
  <p>A listing of specific events marked with the status of &apos;<b>RETAIN</b>&apos; for the selected day.</p>
  <p>All scheduled events for all days will be listed if the choice of &apos;All Days&apos; is selected.</p>
  <p>A download CSV file is created and is available with the same results as shown on the page except that the venue name is in column 1 of each row of the result.</p>
<p>Printing of the report is possible but should be done after doing a print preview and adjusting the print settings appropriately.</p>

<script>
$(document).ready (function() {
  $("#Day").change (function() {
    var day = $("#Day").val();
    console.log("Day: " + day);
    $("#FF").submit();  
    });
  });

</script>';
$daylist = readlistarray('Day');
// echo '<pre>day list before '; print_r($daylist); echo '</pre>';
$daylist[0] = '<option value="all">All Days</option>';
// echo '<pre>day list after '; print_r($daylist); echo '</pre>';
echo '<form id="FF" action="rpteventlisting.php" method="post">
Day: 
<select id="Day" name="Day">
<option value="">Select Day</option>';
foreach ($daylist as $d) {
  echo $d;
}
echo '</select>
</form>
</div> <!-- container -->
</body>
</html>
';

exit;
  }

// create report
echo '
<a class="hidden-print" href="downloads/eventlisting.csv">DOWN LOAD RESULTS</a><span title="Download file with quoted values and comma separated fields" class="hidden-print glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px;"></span>';

//Type	Trip	TypeOfEvent	Day	StartTime	EndTime	Event	Site	SiteRoom	MaxAttendees	Leader1	Leader2	Leader3	Leader4

$sql = '
SELECT * FROM `events` 
WHERE `Day` =   "'.$day.'"
  AND `TripStatus` LIKE "%Retain%" 
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;
';
if ($day == 'all') { 
$sql = '
SELECT * FROM `events` 
WHERE `TripStatus` LIKE "%Retain%" 
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;';
$day = 'All Days';
}

// echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<h3>Listing for: '.$day.'</h3>
row count: '.$rc.'<br>';

$csvmask = '"%s","%s","%s","%s",%s,%s,"%s","%s","%s",%s,"%s","%s","%s","%s"'."\n";
$csv = 'Type,Trip,TypeOfEvent,Day,StartTime,EndTime,Event,EventVenue,EvtVenueInst,MaxAttendees,Leader1,Leader2,Leader3,	Leader4'."\n";

echo '<table class="sortable table"><thead>
<tr><th>Type</th><th>Trip</th><th>Type</th><th>Day</th><th>StartTime</th><th>EndTime</th><th>Event</th><th>EventVenue</th><th>EvtVenueInst</th><th>MaxAttendees</th><th>Leader1</th><th>Leader2</th><th>Leader3</th><th>Leader4</th></tr></thead><tbody>';

while ($r = $res->fetch_assoc()) {
  $st = date("g:i A", strtotime($r['StartTime']));
  $et = date("g:i A", strtotime($r['EndTime']));
  $stv = strtotime($r['StartTime']);
  $etv = strtotime($r['EndTime']);
  $mask = '
<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td data-value="'.$stv.'">%s</td><td data-value="'.$etv.'">%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>';
  
  printf($mask,$r['Type'],$r['Trip'],$r['TypeOfEvent'],$r['Day'],$st,$et,$r['Event'],$r['Site'],$r['SiteRoom'],$r['MaxAttendees'],$r['Leader1'],$r['Leader2'],$r['Leader3'],$r['Leader4']);
  $csv .= sprintf($csvmask,$r['Type'],$r['Trip'],$r['TypeOfEvent'],$r['Day'],$st,$et,$r['Event'],$r['Site'],$r['SiteRoom'],$r['MaxAttendees'],$r['Leader1'],$r['Leader2'],$r['Leader3'],$r['Leader4']);
  
//  echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  }
echo '</tbody></table>';

// echo "<pre> csv \n"; print_r($csv); echo '</pre>';
file_put_contents("downloads/eventlisting.csv", $csv);

?>
</body>
</html>