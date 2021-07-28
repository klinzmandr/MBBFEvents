<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Venue Update</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">

<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript" src="js/jquery-form-restore.js"></script>

<div class="container">
<?php
// error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$vencode = isset($_REQUEST['vencode']) ? $_REQUEST['vencode'] : "1";

// PROCESS UPDATE ACTION IF INDICATED
if ($action == 'update') {
  unset($_REQUEST['action']);
  $flds = array();
  $flds = $_REQUEST['flds'];
  // $flds[VenGmapURL] = htmlentities($flds[VenGmapURL], ENT_QUOTES);
  $flds['VenInsurInfo'] = htmlentities($flds['VenInsurInfo'], ENT_QUOTES);
  $flds['VenSpecNotes'] = htmlentities($flds['VenSpecNotes'], ENT_QUOTES);
  $venid = $flds['VenID'];
	// echo '<pre> full update '; print_r($flds); echo '</pre>';
  $stat  = sqlupdate('venues', $flds, '`VenID` = "'.$venid.'";');
  $vencode = $flds['VenCode'];

  echo '
<script>
$(document).ready(function() {
  $("#X").fadeOut(5000);
});
</script>';
  if ($stat) 
    echo '<h3 style="color: red; " id="X">Update Completed.</h3>';
  else
    echo '<h3 style="color: red; " id="X">Update Failed. Stat: '.$stat.'</h3>';
 
  }

// ----------------- display event info ---------------------   
$sql = "
SELECT * FROM `venues` WHERE `VenCode` = '$vencode';";

// echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

if ($rc == 0) {
  echo '<h3 style="color: red; ">ERROR: There is no record yet created for venue code '.$vencode.'</h3>
  This might be caused by improper calling of this action.  Please use the following button to relist the venues and select the venue again.<br><br>
  Please add any new venue codes to the approved venue list and make sure all venue codes defined are identifying different, unique venues.  <br><br>Review the help documentation for more informamtion.<br><br>
  <a class="btn btn-danger" href="venlister.php">List Current Venues</a>';
  exit;
  }
  
if ($rc > 1) {
  echo '<h3 style="color: red; ">ERROR: More than one venue shares the same venue code.</h3>
  Please contact the system administrator to make sure all venue codes defined are identifying unique venues<br><br>
  <a class="btn btn-danger" href="venlister.php">List Current Venues</a>';
  exit;
  }

$r = $res->fetch_assoc();
//echo '<pre> full record '; print_r($r); echo '</pre>';
if ($r['VenCode'] == '--New--') $r['VenCode'] = '';

?>
<script>
function confirmContinue() {
	var r=confirm("This action cannot be reversed.\n\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

<script>
function validate() {
  var v = new String($("#Notes").val());
  v = v.replace(/\<|\>/g, "");
  $("#Notes").val(v);
  return true;
  }
</script>

<!-- FORM FIELD DEF's -->
<table border="0" width="100%" class="hidden-print">
<tr><td> 
<font size="+2"><b>Update Venue</b></font>
&nbsp;&nbsp;&nbsp;&nbsp;
<button form="F1" class="updb btn btn-success" type="submit">UPDATE VENUE</button></td>
</tr>
</table>
<script>
// get site code from Site drop down list and update site code fields
$(document).ready(function() {
    $("#Site").hide();
    $("#help").hide();
$("#Site").change(function() {
    var r=confirm("This changes the venue name.\n\nConfirm this action by clicking OK or CANCEL to ignore."); 
    if (r==false) { 
      $("#Site").val('');
      chgFlag = 0;
      $('.updb').prop('disabled', true);    
      $(".updb").css({"background-color": "green", "color":"white"});      
      return true; }
    var x = $("#Site").val();
    var parts = x.split(":");
    $("#SiteName").text(parts[0]);
    $("#SiteNameIn").val(parts[0]);
    $("#SiteCode").text(parts[1]);
    $("#SiteCodeIn").val(parts[1]);
  });
$("#ACbtn").click(function() {
  $("#Site").toggle();
  });
$("#helpclk").click(function() {
  $("#help").toggle();
});

});
</script>

<form id="F1" action="venupdate.php" method="post" onsubmit="return validate()">
<input id="VenID" type="hidden" name="flds[VenID]" value="<?=$r['VenID']?>">
<table border="0">
<tr id="help"><td>
Lorem ipsum dolor sit amet, consecteteur adipiscing elit pretium sollicitudin elementum magna nunc. A urna risus vitae amet. Fames lacus dolor, a, taciti penatibus parturient nam justo semper. Vestibulum quis porta ac consequat a, habitasse etiam. Proin eleifend eu, nisi diam dolor amet montes venenatis donec felis nunc suspendisse pretium. Mattis sapien, dolor, netus. Quis dolor bibendum, ac. Fermentum bibendum ve etiam ligula tortor consequat. Dictum. Risus, pede bibendum curae nullam, donec odio leo hac dis sem leo. Risus, leo adipiscing parturient et eros orci quis quam. Lacinia in primis lorem ac ac netus cum eget. Fringilla primis. Ac ligula eu, mattis justo parturient molestie quam malesuada tellus, nunc cras. Aptent tincidunt et. A. Amet feugiat class nisl condimentum dui ut justo in molestie. Eni nulla cum curae penatibus facilisi erat ve. Porta conubia, posuere auctor duis pellentesque lobortis vitae. Conubia venenatis. Tempus taciti. Amet.

</td</tr>
<tr><td>
<h3><b>Venue Name:</b> <span id="SiteName"><?=$r['VenName']?></span></h3>
<input id="SiteNameIn" type="hidden" name="flds[VenName]" value="<?=$r['VenName']?>">
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>
<h3><b>Venue Code:</b> <span id="SiteCode"><?=$r['VenCode']?></span></h3>
<input id="SiteCodeIn" type="hidden" name="flds[VenCode]" value="<?=$r['VenCode']?>">
</td>
</tr>
</table>
<table>
<tr><td colspan="3">
<b>Venue Address:</b> 
<input type="text" name="flds[VenAddr]" value="<?=$r['VenAddr']?>" size="40" id="Event">
</td></tr>
</table>
<table>
<tr><td>
<b>Venue Address 2:</b> 
<input size="40" type="text" name="flds[VenAddr2]" value="<?=$r['VenAddr2']?>">&nbsp;
</td></tr>
</table>
<table>
<tr><td>
<b>Venue City:</b> 
<input size="40" type="text" name="flds[VenCity]" value="<?=$r['VenCity']?>">
</td><td>
<b>Venue State: </b>
<input size="4" type="text" name="flds[VenState]" value="<?=$r['VenState']?>" maxlength="2">
</td><td>
<b>Venue Zip: </b>
<input size="10" maxlength="10" type="text" name="flds[VenZip]" value="<?=$r['VenZip']?>">
</td></tr>
</table>
<table>
<tr><td>
<b>Venue Phone:</b> 
<input size="40" type="text" name="flds[VenPhone]" value="<?=$r['VenPhone']?>">&nbsp;
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Web URL:</b> 
<input size="40" type="text" name="flds[VenWebURL]" value="<?=$r['VenWebURL']?>">&nbsp;
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Contact Name:</b> 
<input size="40" type="text" name="flds[VenContactName]" value="<?=$r['VenContactName']?>">&nbsp;
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Contact Phone:</b> 
<input size="40" type="text" name="flds[VenContactPhone]" value="<?=$r['VenContactPhone']?>">&nbsp;
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Contact Email:</b> 
<input size="40" type="text" name="flds[VenContactEmail]" value="<?=$r['VenContactEmail']?>">&nbsp;
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Google Maps URL Information:</b><br>
NOTE: this must be obtained from the Google Maps web site.<br> 
<textarea name="flds[VenGmapURL]" rows="5" cols="100"><?=$r['VenGmapURL']?></textarea>
</td><td></td></tr>
</table>
<table>
<tr><td>
<b>Venue Insurance Information:</b><br> 
<textarea name="flds[VenInsurInfo]" rows="5" cols="100"><?=$r['VenInsurInfo']?></textarea>
</td><td></td></tr>
</table>
<table border=0>
<tr><td>
<b>Special Notes:</b><br>
<textarea name="flds[VenSpecNotes]" rows="5" cols="100"><?=$r['VenSpecNotes']?></textarea>
</td</tr>
</table>

<input type="hidden" name="action" value="update">
</form>
<div class="hidden-print" align="center">
<button name=reset title="Cancel all changes and restore form to its initial state.">RESET FORM</button>&nbsp;&nbsp;&nbsp;&nbsp;
<button form="F1" class="updb btn btn-success" type="submit">UPDATE VENUE</button></div>
<br><br><br><br>

</div> <!-- container -->

</body>
</html>