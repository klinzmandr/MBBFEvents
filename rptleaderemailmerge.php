<?php session_start(); 
$lt = isset($_REQUEST['lt']) ? $_REQUEST['lt'] : 'both';
// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$type = isset($_REQUEST['Type']) ? $_REQUEST['Type'] : "";

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mail Merge Export</title>
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
  $("#help").hide();
  if ('<?=$lt?>' == 'el') $("#el").attr("checked", true);
  if ('<?=$lt?>' == 'dl') $("#dl").attr("checked", true);
  if ('<?=$lt?>' == 'both') $("#both").attr("checked", true);

$(".ltype").change(function() {
  $('form').submit();
  });

$("#helpbtn").click (function (){
  $("#help").toggle();
  });
});
</script>

<div class="container">
<h3>Leader Email Merge Extract&nbsp;&nbsp;
<span id="helpbtn" title="Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span></h3>
<div id=help>
<p>This extract examines all &quot;active&quot; events and creates a list of the email addresses for all the leaders that are assigned ANY leader position.</p>
<p>The report output is to be highlighted then copy/pasted into the email client.</p>
</div>
<form 'action=rptleaderemailmerge.php'>
Leader type:  <input class=ltype type=radio name=lt id=el value=el>Event&nbsp;&nbsp;
<input class=ltype type=radio name=lt id=dl value=dl>Day&nbsp;&nbsp;
<input class=ltype type=radio name=lt id=both value=both checked>Both<br>
</form>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

// create array of leader names and email addresses
$sql = 'SELECT * FROM `leaders` WHERE 1=1';
if ($lt == 'el')
  $sql = 'SELECT * FROM `leaders` WHERE `LdrEvent` = "TRUE";';
if ($lt == 'dl')
  $sql = 'SELECT * FROM `leaders` WHERE `LdrDay` = "TRUE";';
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
while ($r = $res->fetch_assoc()) {
  $key = $r['FirstName'] . ' ' . $r['LastName'];
  $key = rtrim($key, ' ');
  $leaderemail[$key] = $r['Email'];
  $leadername[$key] = $key;
  }
// echo '<pre> email array '; print_r($leaderemail); echo '</pre>';

// all leaders -> email in $leaderemail array
$sql = '
SELECT `events`.*
FROM `events` 
WHERE 1 = 1
  AND `TripStatus` NOT LIKE "Delete"  
ORDER BY `events`.`Dnbr` ASC, `events`.`StartTime` ASC, `events`.`EndTime` ASC;';

$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// building this array will eliminate any dup email addresses.
while ($r = $res->fetch_assoc()) {
  $emarray[$leaderemail[$r['Leader1']]] = $leadername[$r['Leader1']]; 
  $emarray[$leaderemail[$r['Leader2']]] = $leadername[$r['Leader2']];
  $emarray[$leaderemail[$r['Leader3']]] = $leadername[$r['Leader3']]; 
  $emarray[$leaderemail[$r['Leader4']]] = $leadername[$r['Leader4']]; 
  }
echo '<br>Active event count: '.$rc.'<br>';
echo 'Leader email count: '.count($emarray).'<br>';
// echo '<pre> emarray '; print_r($emarray); echo '</pre>';

echo '<pre>';
foreach ($emarray as $k => $v) {
  if ($k == '') continue;
  echo "$v &lt;$k&gt;,\n";
  } 
echo '</pre>';

?>
</div>  <!-- container -->
</body>
</html>