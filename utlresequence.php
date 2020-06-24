<?php session_start(); 
error_reporting(E_ERROR | E_WARNING | E_PARSE);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Resequence Events</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
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
<div class="container">
<?php
//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';
include 'Incls/checkcred.inc.php';

if ( !checkcred('ReSeq') ) {
//  echo "pw passed<br>";
  echo 'Incorrect password entered for administrative access.<br>';
  exit;
  }

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$day = isset($_REQUEST['Day']) ? $_REQUEST['Day'] : "";

echo '<h3>Resequence Events</h3>';

if ($action == '') {
  echo '
<script>
function chkday() {
var d = $("#Day").val();
if (d == "") {
  alert("Please select a day.");
  return false;
  }
return true;
}
</script>
    <p>This utility will assign a new sequence number to an event based on the day of the event. If the day for the event is the first day of the Festival the number is a 100 series number, the second day events are a 200 series, etc.  Festival days are defined in the &apos;Utilities &gt; List Maintenance -&gt; Day&apos; configuration file.</p>

    <p>First ALL events with a &apos;RETAIN&apos; status are selected for the requested day. The results are sorted by start time, end time within start time, and event title within end time within start time. The number is created by assigning a sequential number within the series based on the day of the Festival.</p>

    <p>If an event is to be assigned a different day or a new or duplicated event is created there is a probabilility that the trip number will be duplicated. The re-sequence utility should be run periodically to eliminate these duplications.</p>

<form id="FF" action="utlresequence.php" method="post" onsubmit="return chkday();">
Select Day:
<select id="Day" name="Day">';
echo readlist('Day');
echo '</select>
<input type="hidden" name="action" value="reseq">
<button type="submit" form="FF" class="btn btn-primary">Resequence selected day</button>
</form>
';
exit;
  }

// setup day sequence number array  
$dayarray = array();
$days = readlistarray('Day');
$daynbr = 1;
foreach ($days as $v) {
  preg_match('/^.*>(.*)<.*$/i', $v, $matches);
  if ($matches[1] == 'Day') continue;
  // echo '<pre>'; print_r($matches[1]); echo '</pre>';
  $dayarray[$matches[1]] = $daynbr;
  $daynbr += 1;
  }
// echo '<pre>dayarray 2 '; print_r($dayarray); echo '</pre>';

if ($action == 'reseq') {
//$sql = 'SELECT * FROM `events` WHERE `Day` = "'.$day.'";';
$sql = '
SELECT * FROM `events` 
WHERE `Day` =   "'.$day.'"
  AND (`TripStatus` = "Retain" OR `TripStatus` = "New")  
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;
';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
$ec = $rc;
$sc = 0;

$seqstart = sprintf("%1d%02d", $dayarray[$day],1); 
$seqend = sprintf("%1d%02d", $dayarray[$day],$rc);

echo "
<p>There are $rc events for $day that will have their trip number resequenced.</p>
<p>Sequence numbers will range from $seqstart to $seqend.</p>
";
echo '<a href="utlresequence.php" class="btn btn-primary">Re-start process</a><br><br>';
echo '
<script>
$(document).ready(function() {
  $("#AP").click(function() {
    $("#IMG").attr("style","visibility-visible");
    });
});
</script>
<a id="AP" href="utlresequence.php?action=apply&Day='.$day.'" class="btn btn-primary">Apply</a>
<br><br>
<img id="IMG" style="visibility: hidden;" src="img/progressbar.gif" width="226" height="26" alt="">
';

exit;
}

//echo 'apply changes<br>';
$sql = '
SELECT * FROM `events` 
WHERE `Day` =   "'.$day.'"
  AND (`TripStatus` = "Retain" OR `TripStatus` = "New")  
ORDER BY `Dnbr` ASC, `StartTime` ASC, `EndTime` ASC;
';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

$dayindex = $dayarray[$day];
$seqstart = ($dayarray[$day]*100) + 1 ;
$results = ''; $updarray = array();
while ($r = $res->fetch_assoc()) {
  // echo '<pre> full record for '.$rowid.' '; print_r($r[Trip]); echo '</pre>';
  $rowid = $r[RowID];
  $results .= "$r[Trip] => $seqstart, ";
  $updarray[Trip] = $seqstart;
  $updarray[Day]  = $day;
  $updarray[Dnbr] = $dayindex;
  sqlupdate('events', $updarray, '`RowID` = "'.$rowid.'";');
  $seqstart += 1;
  }
  
addlogentry('Reseq of '.$day.' completed');
  
echo '
<h3>Resequencing for '.$day.' completed</h3>
The following is a summary of the old and new numbers assigned (OldNbr => NewNbr):
<div class="well">
'.$results.'
</div>  <!-- well -->

';

?>
</div> <!-- container -->
</body>
</html>