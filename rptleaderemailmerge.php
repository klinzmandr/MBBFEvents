<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Mail Merge Export</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
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
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$type = isset($_REQUEST['Type']) ? $_REQUEST['Type'] : "";

echo '
<div class="container">
<h1>Leader Email Merge Extract
<a href="rptindex.php" class="hidden-print btn btn-primary">RETURN</a></h1>
';


echo '
<p>This extract examines all &quot;active&quot; events and creates a CSV file to be used for mail merge processing.</p>
<p>The output file contains 1 line/row for each event with the name of &quot;Leader 1&quot; along with their email address and other event informaton.</p>
<p>The initial output is sorted in the sequence of event day, start time within the event day, end time within the event day and start time.</p>
';

// create report
echo '
<a class="hidden-print" href="downloads/leadermailmerge.csv">DOWN LOAD RESULTS</a><span title="Download file with quoted values and comma separated fields" class="hidden-print glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px;"></span>';

// create array of leader names and email addresses
$sql = '
SELECT * FROM `leaders` WHERE 1=1';
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
while ($r = $res->fetch_assoc()) {
  $key = $r[FirstName] . ' ' . $r[LastName];
  $leaderemail[$key] = $r[Email];
  }
//echo '<pre> email array '; print_r($leaderemail); echo '</pre>';

$sql = '
SELECT `events`.*
FROM `events` 
WHERE 1 = 1
  AND `TripStatus` NOT LIKE "Delete"  
ORDER BY `events`.`Dnbr` ASC, `events`.`StartTime` ASC, `events`.`EndTime` ASC;';


//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
echo '<br>row count: '.$rc.'<br>';
// Fields
// Leader	Day	Day#	StartTime	EndTime	Trip	Event	TripStatus	Duration	Email	Leader1	Leader2	Leader3	Leader4

$csvmask = '"%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s","%s",'."\n";
$csv = 'Leader,Day,Day#,StartTime,EndTime,Trip,Event,TripStatus,Duration,Email,Leader1,Leader2,Leader3,Leader4'."\n";

while ($r = $res->fetch_assoc()) {
  if ($r[Event] == '**New Event**') continue;
  $st = date("g:i A", strtotime($r[StartTime]));
  $et = date("g:i A", strtotime($r[EndTime]));
  $dur = timediff($r[StartTime], $r[EndTime]);
  $key = $r[Leader1]; 
  $em = $leaderemail[$key];
  //echo "key: $key, em: $em<br>";
  $csv .= sprintf($csvmask,$r[Leader1],$r[Day],$r[Dnbr],$st,$et,$r[Trip],$r[Event],$r[TripStatus],$dur,$em,$r[Leader1],$r[Leader2],$r[Leader3],$r[Leader4]);
  
// echo '<pre> event record '; print_r($r); echo '</pre>';
  }

// echo "<pre> csv \n"; print_r($csv); echo '</pre>';
file_put_contents("downloads/leadermailmerge.csv", $csv);

function timediff($start, $end) {
  $tp1val = strtotime($start);
  $tp2val = strtotime($end);
  $diff = $tp2val - $tp1val;
  $hrs = sprintf("%s", floor($diff/3600));   // diff in hours
  $mins = (($tp2val - $tp1val) - ($hrs * (60*60)))/60;   // diff in min
  if ($mins == 0) $fmtdiff = sprintf("%2d Hour(s)", $hrs); 
  else $fmtdiff = sprintf("%2d Hour(s) %2d Min", $hrs, $mins);
  return($fmtdiff);
  }

?>
</div>  <!-- container -->
</body>
</html>