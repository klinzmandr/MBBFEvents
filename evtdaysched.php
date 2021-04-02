<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Event Day Schedule</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
<link href="css/bootstrap-sortable.css" rel="stylesheet">

</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/js.cookie.js" type="text/javascript"></script>
<script src="js/bootstrap-sortable.js"></script>
<script>
$(function() {
  <!-- $.bootstrapSortable({ sign: 'AZ' }) -->
  var val = Cookies.get("setval");
  if (typeof val !== 'undefined') {
    Cookies.set('selval','All') } ;

$("#refbtn").click(function() {
  // alert("refresh button click");
  var sv = $("#daylist").val();
  // set cookie with last selection
  Cookies.set('selval', 'All');
  console.log("selval: "+sv);
  window.location.assign('evtdaysched.php');
  });
  
});
</script>

<script>
$(document).ready(function() {
  $("#helptext").hide();

// initialize table cols on document load  
$('td:nth-child(1),th:nth-child(1)').hide();
$('td:nth-child(2),th:nth-child(2)').hide();

$("#ecnt").html($("tr:visible").length - 1);
var refreshval = Cookies.get('selval');
if (typeof refreshval === 'undefined') { refreshval = 'All' }
console.log("refval: "+refreshval);
$("#daylist").val(refreshval);
var cl = '.'+refreshval;
if ($("#daylist").val() == 'All') {
  $("tr").show();   // show all
  }
else {
  $("tr").hide();   // hide all
  $(cl).show();     // show only evts requested
  }
updaterecnos();     // update nav pointer array


$("#help").click (function (){
  $("#helptext").toggle();
  });

});
</script>

<h3>Events Schedule
<span id="help" title="Help" class="hidden-print glyphicon glyphicon-info-sign" style="color: blue; font-size: 20px"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button id="refbtn" class="btn btn-primary" title="Restore listing to show all events">RESET</button>
<!-- <button class="btn btn-primary" onclick="javascript: window.location.assign('evtdaysched.php');">REFRESH</button> -->
</h3>

<div id="helptext">
<p>This listing provides the scheduled actvities with a status of &apos;<b>RETAIN</b>&apos; for all festival days that have scheduled events.  Individual days can then be selectively listed by choosing from the drop down selections.</p>
<p>After selection of the festival day clicking any event row in the listing will open a new tab with the edit/update page for that event.  Navigation forward, backward, to the start or to the end of the lising can be done by clicking the navigation buttons at the top of the page.  Click the &apos;DONE&apos; button when all updates have been done to close the tab/page and display the listing page.</p>
<p>The default sort order is by festival day ascending and start time within each day.  Sort the table by the desired column BEFORE selection of a specific day to allow the new sort order for individual day's events.</p>
<p>Remember: the event number is not relevant since these are dynamic based on the festive day and start times.  Event numbers can change based  on the day and time they are scheduled when the day&apos;s events are re-sequenced.</p>
<p>Printing of the report is possible but should be done after doing a print preview and adjusting the print settings appropriately.  Printing of the entire list can be done by selecting the &apos;Print Last List&apos; function from the events menu.</p>
</div>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

// echo '<h3>'.$site.'</h3>';
if ($day == '') $day = '%';
else list($site, $code) = explode(':', $site);
$sql = '
SELECT * FROM `events` 
WHERE `TripStatus` = "Retain" 
ORDER BY `Dnbr` ASC,`StartTime` ASC, `EndTime` ASC;';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;
// echo "rc: $rc<br>";
// Day	Start Time	Event (count)
//  second line (hidden): Site Event  1 line per event
$eventarray = array();
$rows = '';
$navarray = array(); $var = array(); $ptr = 0;
while ($r = $res->fetch_assoc()) {
  // echo '<pre>'; print_r($r); echo '</pre>';
  $navarray[] = $r['RowID'];
  $duration = timediff($r['StartTime'], $r['EndTime']);
  $dsecs = dursecs($r['StartTime'], $r['EndTime']);
  $stv = strtotime($r['StartTime']);
  $etv = strtotime($r['EndTime']);
  $rows .= "<tr class='$r[Day]' style='cursor: pointer'>";
  $rows .= "<td>$ptr</td>";
  $rows .= "<td>$r[RowID]</td>";
  $rows .= "<td data-value='$r[Dnbr]'>$r[Day]</td>";
  $rows .= "<td data-value='$stv'>$r[StartTime]</td>";
  $rows .= "<td data-value='$etv'>$r[EndTime]</td>";
  $rows .= "<td data-value='$dsecs'>$duration</td>";
  $rows .= "<td>$r[Trip]</td>";
  $rows .= "<td>$r[Event]</td>";
  $rows .= "<td>$r[Site]</td>";
  $rows .= "<td>$r[Site2]</td>";
  $rows .= "</tr>\n";
  $ptr += 1;

  }

// setup day sequence number array  
$dayarray = array();
$days = readlistarray('Day');
$daynbr = 1;
foreach ($days as $v) {
  preg_match('/^.*>(.*)<.*$/i', $v, $matches);
  if ($matches[1] == 'Day') continue;
  $dayarray[$matches[1]] = $daynbr;
  $daylist .= "<option value='$matches[1]'>$matches[1]</option>\n";
  $daynbr += 1;
  }
  
?>

<script>
$(function() {
  $("#daylist").change(function() {
    // alert("day list changed");
    // console.log($("#daylist").val());
    var clx = $("#daylist").val();
    var cl = '.'+clx;
    // console.log(cl);
    if ($("#daylist").val() == 'All') {
      $("table").addClass("sortable");
      $("tr").show();
      }
    else {
      $("table").removeClass("sortable");
      $("tr").hide();
      $(cl).show();
      }
    Cookies.set('selval', clx);
    updaterecnos();
  });
});
 
function updaterecnos() {
  var recnoarr = [];
  var vr = $("tr:visible");
  var vrarr = vr.each(function () {
    var el = $(this).find('td:nth-child(2)').text();
    // console.log("el: "+el);
    if (el != '') recnoarr.push(el);
    });
  // console.log("recnoarr: "+recnoarr);
  $("#head").show();
  $("#ecnt").html($("tr:visible").length - 1);
// update session vars with new rec nbr list
  $.post("evtdayschedJSONsetsess.php",
    {
    sessArray: recnoarr
    },
  function(data, status){
      // alert("Data: " + data + "\nStatus: " + status);
      });  // end $.post logic 
    }

$(function() {
  $("tr").click(function() {
    // alert("row click event");
    var r = $(this);  // row clicked
    if (r.prop("id") == 'head') return;
    var rownbr = $(r).closest('tr').find('td:nth-child(1)').text();
    // console.log("rownbr: "+rownbr);
    var ptr = 0; count = 0;
    $("tr").each(function() {
      p = $(this).find('td:nth-child(1)').text();
      if ($(this).is(":visible")) {
        if (p == rownbr) {
          ptr = count;  
          return false; }
        count += 1;
        }
      console.log("p: "+p); 
      });    
    ptr -= 1;
    // console.log("ptr: "+ptr);
    // window.location.assign('evtupdateevent.php?ptr='+ptr);
    window.open("evtupdateevent.php?ptr="+ptr, "_blank");
    });
  });
</script>

Events listed: <span id="ecnt"></span>
<select id="daylist" name="Site">
<option value="">Select Festival Day</option>';
<option value="All">All Days</option>';
<?=$daylist?>
</select>

<table class="sortable table table-hover">
<thead>
<tr id="head"><th>Ptr</th><th>RecNo<th>Day</th><th>StartTime</th><th>EndTime</th><th>Duration</th><th>Trip</th><th>Event</th><th>Venue</th><th>MeetingSite</th></tr>
</thead>
<tbody>
<?=$rows?>
</tbody></table>
</body>
</html>

<?php
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

function dursecs($start, $end) {
  $tp1val = strtotime($start);
  $tp2val = strtotime($end);
  return($tp2val - $tp1val);
  }
?>
