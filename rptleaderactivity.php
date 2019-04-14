<?php session_start(); 
$lt = isset($_REQUEST['lt']) ? $_REQUEST['lt'] : 'both';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Leader Activity</title>
<!-- Bootstrap -->
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

<h3>Leader Activity&nbsp;&nbsp;
<span id="helpbtn" title="Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span></h3>
<div id=help>
<p>The report lists all event leaders that have assigned events in at least one of the four leader roles.  Each leader is listed with their assignments listed by day and event start time and duratiion hours. The event location and name are also listed.</p>
<p>By default both Event and Day Leaders are included in the report.</p>
</div>
<form 'action=rptleaderactvity.php'>
Leader type:  <input class=ltype type=radio name=lt id=el value=el>Event&nbsp;&nbsp;
<input class=ltype type=radio name=lt id=dl value=dl>Day&nbsp;&nbsp;
<input class=ltype type=radio name=lt id=both value=both checked>Both<br>
</form>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';
//include 'Incls/listutils.inc.php';

// select leaders to include
$ldrsql = 'SELECT * FROM `leaders` WHERE 1=1;';
if ($lt == 'el')
  $ldrsql = 'SELECT * FROM `leaders` WHERE `LdrEvent` = "TRUE";';
if ($lt == 'dl')
  $ldrsql = 'SELECT * FROM `leaders` WHERE `LdrDay` = "TRUE";';
// echo "sql: $ldrsql<br>";
$ldrres = doSQLsubmitted($ldrsql);
$ldrrc = $ldrres->num_rows;
// echo "ldrrc: $ldrrc<br>";
while ($l = $ldrres->fetch_assoc()) {
  $fullname = $l[FirstName] . ' ' . $l[LastName];
  $fullname = rtrim($fullname, ' ');
  $leaders[] = $fullname;
  }
// echo '<pre>leaders '; print_r($leaders); echo '</pre>';

// generate leader activity report
// excluding events that are marked as deleted
$sql = '
SELECT * FROM `events` WHERE `TripStatus` NOT LIKE "%Delete%";';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

//report cols: Leader	Day	StartTime	Duration	TripStatus Site Event
$ldrarray = array();
while ($r = $res->fetch_assoc()) {
  // echo '<pre> full record for '.$rowid.' '; print_r($r); echo '</pre>';
  if ($r[Day] == 'Friday') $d = 1; if ($r[Day] == 'Saturday') $d = 2;
  if ($r[Day] == 'Sunday') $d = 3;if ($r[Day] == 'Monday') $d = 4;
  if ($r[Leader1] != '') 
  $ldrarray[$r[Leader1]] [$d] [$r[StartTime]] = "$r[EndTime]/$r[Day]/$r[TripStatus]/$r[Site]/$r[Event]";
  if ($r[Leader2] != '')
  $ldrarray[$r[Leader2]] [$d] [$r[StartTime]] = "$r[EndTime]/$r[Day]/$r[TripStatus]/$r[Site]/$r[Event]";
  if ($r[Leader3] != '')
  $ldrarray[$r[Leader3]] [$d] [$r[StartTime]] = "$r[EndTime]/$r[Day]/$r[TripStatus]/$r[Site]/$r[Event]";
  if ($r[Leader4] != '')
  $ldrarray[$r[Leader4]] [$d] [$r[StartTime]] = "$r[EndTime]/$r[Day]/$r[TripStatus]/$r[Site]/$r[Event]";  
  }
ksort($ldrarray);
// echo '<pre> leaderarray '; print_r($ldrarray); echo '</pre>';

foreach ($ldrarray as $k => $v) {
  if (!in_array($k, $leaders)) continue;
  echo "<h4>$k</h4>";
  //echo "<pre> leader $k "; print_r($v); echo '</pre>';
  foreach ($v as $kk => $vv) {
    //echo "Day $kk<br>";
    echo '<ul>';
    if ($kk == 1) $dx='Friday '; if ($kk == 2) $dx='Saturday ';
    if ($kk == 3) $dx='Sunday '; if ($kk == 4) $dx='Monday ';
    // echo "<pre> day $kk "; print_r($vv); echo '</pre>';
    foreach ($vv as $kkk => $vvv) {
      //echo "$kkk ";
      //echo "<pre> hour $kkk "; print_r($vvv); echo '</pre>';
      list($endtime, $day, $tripstatus, $site, $event) = explode('/',$vvv);
      $dur = timediff($kkk, $endtime);
      $st = date("g:i A", strtotime($kkk));
      $et = date("g:i A", strtotime($r[EndTime]));
      
      echo "$dx $st (Duration: $dur) <b>site:</b> $site <b>event:</b> $event<br>";
      }
    echo '</ul>';
    }
  }

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
</div> <!-- container -->
</body>
</html>