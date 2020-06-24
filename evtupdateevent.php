<?php session_start(); 
date_default_timezone_set('America/Los_Angeles');

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Event Update</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link rel="stylesheet" href="css/jquery.timepicker.css" type="text/css"/>
<link rel="stylesheet" href="css/bootstrap-multiselect.css" type="text/css"/>
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-multiselect.js" type="text/javascript"></script>
<script src="js/jquery-form-restore.js" type="text/javascript"></script>

<style>
.default {
  cursor: default;
  }
.mod { 
  color: blue; 
  font-weight: 
  bold; text-decoration: 
  underline; 
  cursor: pointer;  
  }
</style>

<div class="container">
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php'; 

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$rowid = isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : "1";

//echo '<pre> REQUEST '; print_r($_REQUEST); echo '</pre>';
$navarray = $_SESSION['navarray'];  // array of record numbers from last search
$nav = $_SESSION['nav'];            // array first, prev, curr, next and last
$ptr = $_REQUEST['ptr'];            // index of record number array 

//echo '<pre> navarray '; print_r($navarray); echo '</pre>';
//echo '<pre> BEFORE '; print_r($nav); echo '</pre>';
$nav['curr'] = $ptr;
$nav['prev'] = $nav['curr'] - 1; if ($nav['prev'] < 0) $nav['prev'] = 0;
$nav['next'] = $nav['curr'] + 1; if ($nav['next'] > $nav['last']) 
$nav['next'] = $nav['last'];
//echo '<pre> AFTER '; print_r($nav); echo '</pre>';

// PROCESS UPDATE ACTION IF INDICATED
if ($action == 'update') {
  // first setup event day sequence number array 
  // based on 'Day' config values
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
  // echo '<pre>dayarray '; print_r($dayarray); echo '</pre>';
  // now update the event record
  $flds = array();
  $flds = $_REQUEST['flds'];
  $flds[StartTime] = date("H:i:s", strtotime($flds[StartTime]));
  $flds[EndTime] = date("H:i:s", strtotime($flds[EndTime]));
  
  // day seq nbr based on day of event
  $flds[Dnbr] = $dayarray[$flds[Day]]; 

// handle multiselect Event Codes field
  $lvls = isset($_REQUEST['Codes']) ? $_REQUEST['Codes'] : '';
//  print_r($lvls);
  if ($lvls != "") $flds[Level] = implode(",", $lvls);  // string levels for db update
  else $flds[Level] = "";                               // no levels selected for update
// echo "<br>flds[Level]: ".$flds[Level]."<br>";

// handle site:sitecode split - ONLY place site portion into db field
// the SiteCode field is already initialized
  if (isset($flds[Site])) {
    list($s, $sc) = explode(':',$flds[Site]); 
    $flds[Site] = $s;
    }
	$rowid = $flds[RowID]; unset($flds[RowID]);
  sqlupdate('events', $flds, '`RowID` = "'.$rowid.'";');

  echo '
<script>
$(document).ready(function() {
  $("#X").fadeOut(2000);
});
</script>
<h3 style="color: red; " id="X">Update Completed.</h3>
  ';
  }   // END UPDATE ACTION 

// ----- display event info 
$rowid = $navarray[$ptr];  
//echo "ptr: $ptr, rowid: $rowid<br>";
$sql = "SELECT * FROM `events` WHERE `RowID` = '$rowid';";

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

$r = $res->fetch_assoc();
//echo '<pre> full record '; print_r($r); echo '</pre>';
// set up Site field for multiselect initialization
if ($r[Site] != '') {
  $r[Site] = $r[Site] . ':' . $r[SiteCode];
}
?>

<table border="0" class="hidden-print table table-condensed">
<tr>
<td width="33%" valign="top">
<h2>Event Update</h2></td>
<td align="center"><br>
<a class="clk" href="evtupdateevent.php?ptr=<?=$nav['start']?>"><span title="START" class="glyphicon glyphicon-fast-backward" style="color: blue; font-size: 20px;"></span></a>&nbsp;&nbsp;
<a class="clk" href="evtupdateevent.php?ptr=<?=$nav['prev']?>"><span title="PREV" class="glyphicon glyphicon-step-backward" style="color: blue; font-size: 20px;"></span></a>&nbsp;&nbsp;
<a href="evtlister.php" class="clk btn btn-primary">SEARCH</a>&nbsp;&nbsp;
<a class="clk" href="evtupdateevent.php?ptr=<?=$nav['next']?>"><span title="NEXT" class="glyphicon glyphicon-step-forward" style="color: blue; font-size: 20px;"></span></a>&nbsp;&nbsp;
<a class="clk" href="evtupdateevent.php?ptr=<?=$nav['last']?>"><span title="LAST" class="glyphicon glyphicon-fast-forward" style="color: blue; font-size: 20px;"></span></a><br>
</td>
<script>
function confirmContinue() {
	var r=confirm("This action cannot be reversed.\\n\\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

<td width="33%" align="right" valign="center">
<br>
<a class="clk" onclick="return confirmContinue()" href="evtlister.php?rowid=<?=$r[RowID]?>&action=delete"><span title="Remove THIS Event from the database." class="glyphicon glyphicon-trash" style="color: blue; font-size: 30px;"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a class="clk" href="evtduplicateevent.php?rowid=<?=$r[RowID]?>"><span title="Duplicate THIS Event" class="glyphicon glyphicon-duplicate" style="color: blue; font-size: 30px;"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;
<a class="clk" href="evtaddevent.php"><span title="Add NEW Event" class="glyphicon glyphicon-plus" style="color: blue; font-size: 30px"></span></a>
</td></tr></table>

<script>
function validate() {
  var error = "";
  var tp1 = Date.parse("1/1/2016 "+$("#StartTime").val());
  var tp2 = Date.parse("1/1/2016 "+$("#EndTime").val());
  // console.log("starttime: "+tp1+", endtime: "+tp2);
  if (tp1 >= tp2) {
    error += "End time is before or same as start time\n";
    }
  var d = $("#Trip").val();
  if (d.length < 3) {
    error += "New Trip number must be at least 3 digits.\n";
    }
  if (error.length > 0) {
    alert("Please correct the following:\n\n"+error);
    return false;
    }
  var v = new String($("#Program").val());
  v = v.replace(/\<|\>/g, "");
  $("#Program").val(v);
  var v = new String($("#Event").val());
  v = v.replace(/\<|\>/g, "");
  $("#Event").val(v);
  return true;
  }
</script>

<script>
// SELECT FIELD ON-LOAD SETUPS
$(document).ready(function() {
  $("#Day").val("<?=$r[Day]?>");
  $("#Type").val("<?=$r[Type]?>");
  $("#TripStatus").val("<?=$r[TripStatus]?>");
  $("#Transportation").val("<?=$r[Transportation]?>");
  $("#TransportNeeded").val("<?=$r[TransportNeeded]?>");
  $("#FeeRequired").val("<?=$r[FeeRequired]?>");
  $("#MultiEvent").val("<?=$r[MultiEvent]?>");
  $("#TypeOfEvent").val("<?=$r[TypeOfEvent]?>");
  $("#SC").text("<?=$r[SiteCode]?>");
  $("#Site").val("<?=$r[Site]?>");
  $("#SiteCode").val("<?=$r[SiteCode]?>");

// change status field to 'Delete' implies trip number == 999
// and all leader fields set to empty. 
$("#TripStatus").change(function() {
  var loadedts = "<?=$r[TripStatus]?>";
  var newts = $("#TripStatus").val();
  if (newts == 'Delete') {
    r = confirm("This action will:\n1. set the trip status to Delete,\n2. set the trip number to 999,\n3. clear the day field.\n\n\nClick OK to confirm.\n");
    if (r) {
      // alert("Event status set to DELETE");
      $("#Trip").val("999");
      $("#Day").val("Day");
      // $(".LDR").val("");
      // json call to update event record         
      $.post("evtupdateeventjson.php",
        {
          eventrow: "<?=$r[RowID]?>"
        },
    function(data, status){
        // alert("Data: " + data + "\nStatus: " + status);
        // clear submit button and change flag count
        chgFlag = 0;
        $('.updb').prop('disabled', true);    
        $(".updb").css({"background-color": "green", "color":"white"});
        $("#content").html("<h3>Event Update Successful</h3><p>A listing of all 'deleted/parked' events may be obtained by doing a search with the 'Status' field set to 'Delete'.</p><p>An event may be re-used ('un-parked') by merely changing the 'Trip' number and the 'Trip Status' fields.  Other distinguishing fields such as the event day, start time, end time, event leaders, etc. would also, probably, be modified as well.</p><p>Use the trashcan icon to permanently remove an event from the database.</p>"); 
        $("#ModalLabel").text("Event Delete/Park"); 
        $('#ldrModal').modal('toggle', { keyboard: true });
      });  // end $.post logic 
      return;   // end of confirm OK logic 
      }
    // alert("cancelled trip status update"); 
    $("#TripStatus").val(loadedts);
    }
  });  
});
</script>

<?php
$ldrlist = setupta();     // set up type ahead for lead name input fields
//echo "ldrlist: $ldrlist<br>";

// FORM FIELD DEF's
$t = sprintf("%03s",$r[Trip]);
$diff = timediff($r[StartTime],$r[EndTime]);
$stime = ($r[StartTime] != '') ? date("g:i A", strtotime($r[StartTime])) : ''; 
$etime = ($r[EndTime]   != '') ? date("g:i A", strtotime($r[EndTime])) : '';

// set up multi select init string for Level code field
// echo '<pre>lvls '; print_r($lvls); echo '</pre>';
if ($r[Level] != "") {
  $valarray = explode(',',$r[Level]);
  $vals = "['" . implode("','", $valarray) . "']";
  }
else $vals = "[]";
// echo "dblevels: $r[Level], vals: $vals<br>";
?>
<button form="F1" id="updb1" class="updb btn btn-success hidden-print" type="submit">APPLY UPDATES TO EVENT: </button>&nbsp;<font size="+2"><?=$r[Event]?></font>

<form id="F1" action="evtupdateevent.php" method="post" onsubmit="return validate()">
<table border="1">
<input type="hidden" name="flds[RowID]" value="<?=$r[RowID]?>">
<tr><td>
Trip Number: 
<input autofocus type="text" name="flds[Trip]" value="<?=$t?>" size="5" id="Trip">
</td><td>
Day: 
<select id="Day" name="flds[Day]">
<?php echo readlist('Day'); ?>
</select>
</td><td>
Trip Status: 
<select id="TripStatus" name="flds[TripStatus]">
<?php echo readlist('TripStatus'); ?>
</select>
</td></tr>
<tr><td>
Start Time: 
<input type="text" name="flds[StartTime]" value="<?=$stime?>" size="15" class="tpick" id="StartTime">
</td><td>
End Time: 
<input type="text" name="flds[EndTime]" value="<?=$etime?>" size="15" class="tpick" id="EndTime">
</td><td>
Duration: <span id="DUR"><?=$diff?></span>
</td></tr>
<tr><td colspan="3">
Event Name: 
<input type="text" name="flds[Event]" value="<?=$r[Event]?>" size="60" id="Event">
</td>
</tr>
<tr>
<td>
Trip Type:
<select id="Type" name="flds[Type]">
<?php echo readlist('TripType'); ?>
</select>
</td>
<td>
Event Type: 
<select id="TypeOfEvent" name="flds[TypeOfEvent]">
<?php echo readlist('TypeOfEvent'); ?>
</select>
</td>
<script type="text/javascript">
$(document).ready(function () {
  var initValues = <?=$vals?>;
  $("#Codex").val(initValues);
  $("#Codex").multiselect({
    numberDisplayed: 5,
    delimiterText: ",",
    nonSelectedText: "None Selected"
    });
  $("#Codex").multiselect("refresh");
});
</script>
<td>
Event Level: 
<select id="Codex" name="Codes[]" multiple>
<?php echo readlist('EventLevels'); ?>
</select>
</td></tr>
<script>
// get site code from Site drop down list and update site code fields
$(document).ready(function() {
// split site name and site code when it is changed  
$("#Site").change(function() {
    var x = $("#Site").val();
    var parts = x.split(":");
    $("#SC").text(parts[1]);
    $("#SiteCode").val(parts[1]);
  });
// get modal for a leader  
$(".ld").click(function() {
    var ldrname = $(this).parent().find('input').val();
    if (ldrname.length == 0) return;
    // alert("ldr cell clicked for: " + ldrname);
    ldrname = ldrname.replace(/[,\s]/g, "");
    // alert("Modal button clicked: " + ldrname);
    $.post("plannerldrjson.php",
      {
          name: ldrname
      },
      function(data, status){
        // alert("Data: " + data + "\\nStatus: " + status);
        $("#content").html(data); 
        $("#ModalLabel").text("Leader Information"); 
        $('#ldrModal').modal('toggle', { keyboard: true });
        });  // end $.post logic 
      });
 
// get modal for a venue/site  
  $("#VID").click(function() {
    var vencode = $("#SC").html();
    vencode = vencode.replace(/[,\s]/g, "");
    // alert("Modal button clicked: " + vencode);
    $.post("evtvenuejson.php",
      {
        vencode: vencode
      },
      function(data, status) {
        // alert("Data: " + data + "\\nStatus: " + status);
        $("#content").html(data);
        $("#ModalLabel").text("Venue Information"); 
        $('#ldrModal').modal('toggle', { keyboard: true });
        });  // end $.post logic 
    });
});
</script>

<tr><td>
Site:
<select id="Site" name="flds[Site]">
<?php 
$site = readvenlist('Site');
echo $site; 
echo '</select></td>';
// echo '<pre>sites '; print_r(htmlentities($site)); echo '</pre>';
?>
<td class="mod" id="VID">
Site Code: <span id="SC"></span>
<input id="SiteCode" type="hidden" name="flds[SiteCode]" value="">
</td>
<td> 
Site Room: 
<input id="SiteRoom" type="text" name="flds[SiteRoom]" value="<?=$r[SiteRoom]?>">
</td>
</tr>
<tr>
<td valign="top">Site Address or Directions:</td>
<td id="sa" colspan="2">
<textarea name="flds[SiteAddr]" cols="50"  colid="SiteAddr"><?=$r[SiteAddr]?></textarea>
</td>
</tr>
</table>

<table border="0">
<tr><td>
<span class="ld mod">Leader 1:</span> 
<input  class="LDR" data-provide="typeahead" id="Leader1" type="text" name="flds[Leader1]" value="<?=$r[Leader1]?>">
</td>
<td colspan="2">
<span class="ld mod">Leader 2:</span> 
<input class="LDR" data-provide="typeahead" id="Leader2" type="text" name="flds[Leader2]" value="<?=$r[Leader2]?>">
</td></tr><tr>
<td>
<span  class="ld mod">Leader 3:</span> 
<input class="LDR" data-provide="typeahead" id="Leader3" type="text" name="flds[Leader3]" value="<?=$r[Leader3]?>">
</td>
<td>
<span  class="ld mod">Leader 4:</span> 
<input class="LDR" data-provide="typeahead" id="Leader4" type="text" name="flds[Leader4]" value="<?=$r[Leader4]?>">
</td></tr>
</table>
<table border="0">
<tr><td>
Fee Required(Y/N):
<select id="FeeRequired" name="flds[FeeRequired]">
<option value=""></option><option value="Yes">Yes</option><option value="No">No</option>
</select>
</td><td colspan="2">
FEE: 
<input id="FEE" type="text" name="flds[FEE]" value="<?=$r[FEE]?>" size="6" ><br>
</td></tr>
<tr><td>
Transport Needed(Y/N): 
<select id="TransportNeeded" name="flds[TransportNeeded]">
<option value=""></option><option value="Yes">Yes</option><option value="No">No</option>
</select>
</td><td colspan="2">
Transportation:
<select id="Transportation" name="flds[Transportation]">
<?php echo readlist('Transportation'); ?>
</select><br>
</td></tr>
<tr><td>
Maximum Attendees: 
<input type="text" name="flds[MaxAttendees]" value="<?=$r[MaxAttendees]?>" size="5" id="MaxAttendees">
</td><td>
Multi-Event(Y/N): 
<select id="MultiEvent" name="flds[MultiEvent]">
<option value=""></option><option value="Yes">Yes</option><option value="No">No</option>
</select>
</td><td>
Multi Event Code(s): 
<input id="MultiEventCode" type="text" name="flds[MultiEventCode]" value="<?=$r[MultiEventCode]?>">
</td></tr>
<tr><td>
</table>
<table>
<tr><td>
Program Description: <br>
<textarea id="Program" name="flds[Program]" rows="5" cols="100"><?=$r[Program]?></textarea>
</td></tr>
<tr><td valign="top">
Production Notes:<br>
<textarea id="SecondaryStatus" name="flds[SecondaryStatus]" rows="5" cols="100"><?=$r[SecondaryStatus]?></textarea>
</td></tr>
<tr>
<td align="center">
<!-- <button name=reset title="Cancel all changes and restore form to its initial state.">RESET FORM</button>&nbsp;&nbsp;&nbsp;&nbsp; -->
<button form="F1" class="updb btn btn-success hidden-print" type="submit">APPLY UPDATES</button>
</td></tr></table>

<!-- HIDDEN FORM FIELDS -->
<input type="hidden" name="action" value="update">
<input type="hidden" name="ptr" value="<?=$ptr?>">
</form>
</div> <!-- container -->

<!-- Modal definition -->
 <div class="modal fade" id="ldrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="ModalLabel"></h4>
</div>  <!-- modal header -->
<div class="modal-body">

<div id="content" style="overflow-y:scroll; height:400px;">
</div>
</div>  <!-- modal body -->
</div><!-- modal-content -->
</div><!-- modal-dialog -->
</div><!-- modal -->
<!-- end of modal -->

<script type="text/javascript" src="js/jquery.timepicker.js"></script>
<script>
$(document).ready(function(){
$("input.tpick").timepicker({ 
  timeFormat: "h:mm p",
  dynamic:    true,
  startTime:  "7:00 a",
  minTime:    "6:00 a",
  maxTime:    "6:00 p",
  interval:   15,
  scrollbar:  true,
  change: function() {      // note any change to form
    chgFlag += 1; 
    $('.updb').prop('disabled', false);    
    $(".updb").css({"background-color": "red", "color":"black"});
  }
});

});
</script>

<script src="js/bootstrap3-typeahead.js"></script>
<script>
 var ldrs = <?=$ldrlist?>; 
  $("input.LDR").typeahead({source: ldrs});
</script>
</body>
</html>

<?php
// set up type ahead for leader input fields
function setupta() {
  $sql = "SELECT `FirstName`,`LastName` from `leaders` 
  WHERE `Active` = 'YES'
  AND `LdrEvent` = 'TRUE' 
  ORDER BY `LastName` ASC;";
$res = doSQLsubmitted($sql);
$rowcount = $res->num_rows;
// echo "rowcount: $rowcount<br>";
if ($res->num_rows == 0) {
	echo '<h2>No leaders exists to populate the typeahead fields.</h2>
	<br><br>';
	echo '<a class="btn btn-danger" href="evtlister.php">RETURN</a></body></html>';
	exit;
	}
// now create the string for the javascript arrays to download
$ldrs = '[';		// create string for form typeahead
while ($r = $res->fetch_assoc()) {
	$ldrfn = preg_replace("/[\(\)\.\ \/\&]/i", "", $r[FirstName]);
	$ldrln = preg_replace("/[\(\)\.\ \/\'\&]/i", "", $r[LastName]);
	if ($ldrln == '')
  	$ldrs .= "'$ldrfn',";
  else 	
    $ldrs .= "'$ldrfn $ldrln',";
  }
$ldrs = rtrim($ldrs,',') . ']';
return($ldrs);

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