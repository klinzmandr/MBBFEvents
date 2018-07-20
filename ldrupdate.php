<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Leader Update</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery-form-restore.js"></script>
<style>
  input[type=checkbox] { transform: scale(1.5); }
</style> 

<div class="container">
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
$rowid = isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : "1";
$ss = isset($_REQUEST['ss']) ? $_REQUEST['ss'] : "";
$active = isset($_REQUEST['Active']) ? $_REQUEST['Active'] : "";

// echo '<pre> REQUEST '; print_r($_SERVER); echo '</pre>';
$proto = $_SERVER['REQUEST_SCHEME'];
$host = $_SERVER['HTTP_HOST'];
$imgpath = $proto . '://' . 'morrobaybirdfestival.net/mbbfLeaderPics/';
// if ($host == 'localhost') 
//   $imgpath = $proto . '://' . 'localhost/www/dev/mbbfLeaderPics/';  // dev system
// echo "imgpath: $imgpath<br>";

// PROCESS UPDATE ACTION IF INDICATED
if ($action == 'update') {
  $flds = array();
  $flds = $_REQUEST['flds'];
	// echo '<pre> full update '; print_r($flds); echo '</pre>';
  sqlupdate('leaders', $flds, '`RowID` = "'.$rowid.'";');

  echo '
<script>
$(document).ready(function() {
  $("#X").fadeOut(5000);
});
</script>
<h3 style="color: red; " id="X">Update Completed.</h3>
'; 
  }

// ----------------- display event info ---------------------   
$sql = 'SELECT * FROM `leaders` WHERE `RowID` = '.$rowid.';';

//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

$r = $res->fetch_assoc();
// echo '<pre> full record '; print_r($r); echo '</pre>';
if ($r[FirstName] == '**New**') $r[FirstName] = '';

?>

<script>
function confirmContinue() {
	var r=confirm("This action cannot be reversed.\n\nConfirm this action by clicking OK or CANCEL"); 
	if (r==true) { return true; }
	return false;
	}
</script>

<script>
// capture modal link URL and move it into form field
// NOTE: event passed in call to allow preventDefault to work in FFox
function lc(event, link) {
  event.preventDefault();
  var lh = "<?=$host?>";
  if (lh == 'localhost')
    alert("CAUTION: Updates of leader profile with new photo being done on test system.");
  var l = link.getAttribute("href");
  // var lx = "http://localhost/www/dev/mbbfLeaderPics/"+encodeURI(l);
  var lx = "<?=$imgpath?>" + encodeURI(l);
  $("#pic").attr("src", lx);
  $("#hiddenlink").val(lx);
  $("#link").html(l);
  $("#ldrModal").modal("toggle");
  chgFlag += 1; // bump change flag + toggle update button
  $(".updb").css({"background-color": "red", "color":"black"});
  $('.updb').prop('disabled', false);    
  }  

</script>

<script>
function chkupd() {
  if (updcnt > 0) {
    if (confirm("Updates made without saving saving them.\\n\\nCancel action or OK to continue.")) 
      { return true; }
    return false;
    }
  }
</script>
<script>
function validate() {
  if ($("#LDREv").val() == 'TRUE') { }
  else if ($("#LDRDv").val() == 'TRUE') { }
  else { alert("Leader type not specified"); return false; }
  // if ($("#isAgeSelected").is(':checked'))
  var v = new String($("#Notes").val());    // sanitize notes
  v = v.replace(/<|>/g, "");
  $("#Notes").val(v);
  var v = new String($("#Bio").val());      // sanitize bio info
  v = v.replace(/<|>/g, "");
  $("#Bio").val(v);
  v = new String($("#fn").val());           // sanitize first name
  v = v.replace(/'|"/g, "");
  $("#fn").val(v);  
  v = new String($("#ln").val());           // sanitize last name
  v = v.replace(/'|"/g, "");
  $("#ln").val(v);  
  
  return true;
  }
</script>

<script>
$(document).ready(function() {
  $("#ACTIVE").val("<?=$r[Active]?>");
  
  var e = $("#LDREv").val();
  if (e == 'TRUE') $("#LDRE").attr("checked", true);
  var d = $("#LDRDv").val();
  if (d == 'TRUE') $("#LDRD").attr("checked", true);
$("#LDRE").change(function() {
  $("#LDREv").val('FALSE');
  if ($("#LDRE").is(':checked')) 
    $("#LDREv").val('TRUE');
  // alert ("ldrevent changed");
});

$("#LDRD").change(function() {
  $("#LDRDv").val('FALSE');
  if ($("#LDRD").is(':checked')) 
    $("#LDRDv").val('TRUE');
  // alert ("ldrday changed");
});

// change image button clicked;
$(".mod").click(function () {
  var ln = $("#ln").val();
  if (ln.length == 0) ln = $("#fn").val();
  $.post("ldrupdatejson.php",
    {
name: ln
    },
    function(data, status){
      // alert("Data: " + data + "\nStatus: " + status);
      $("#content").html(data); 
      $('#ldrModal').modal('toggle', { keyboard: true });
      });  // end $.post logic 
  eval();
  });

});
</script>

<?php
// FORM FIELD DEF's
$imgurl = 'npa.png';
if ($r[ImgURL] <> '') $imgurl = $r[ImgURL];
?>

<table border="0" width="100%" class="hidden-print">
<tr valign="middle"><td>
<font size="+2"><b><?=$r[FirstName]?> <?=$r[LastName]?></b></font>
&nbsp;&nbsp;&nbsp;&nbsp;
<button form="F1" class="updb btn btn-success" type="submit">UPDATE LEADER</button></td>
<td align="right"><a onclick="return confirmContinue()" 
href="ldrlister.php?rowid=<?=$r[RowID]?>&ss=<?=$ss?>&action=delete&Active=<?=$active?>">
<span title="Delete Leader" class="glyphicon glyphicon-trash" style="color: blue; font-size: 25px;"></span></a>&nbsp;&nbsp;
</td></tr>
</table>

<form id="F1" action="ldrupdate.php" method="post" onsubmit="return validate()">
<table border="0">
<tr><td>
Leader Active?:
<select id="ACTIVE" name="flds[Active]">
<option value=""></option><option value="Yes">Yes</option><option value="No">No</option>
</select>
 
</td>
<td>
<input type=hidden id=LDREv name="flds[LdrEvent]" value="<?=$r[LdrEvent]?>"> 
Leader Type: Event&nbsp;<input type="checkbox" id="LDRE">&nbsp;
<input type=hidden id=LDRDv name="flds[LdrDay]" value="<?=$r[LdrDay]?>"> 
Family Day&nbsp;<input type="checkbox" id="LDRD">
</td>
</tr>
<tr><td>
First Name: 
<input id=fn type="text" name="flds[FirstName]" value="<?=$r[FirstName]?>" autofocus>&nbsp;
</td><td>
Last Name: 
<input id=ln type="text" name="flds[LastName]" value="<?=$r[LastName]?>">
</td></tr>
</table>
<table>
<tr><td>
Primary Phone: 
<input type="text" name="flds[PrimaryPhone]" value="<?=$r[PrimaryPhone]?>">&nbsp;
</td><td>
Secondary Phone: 
<input type="text" name="flds[SecondaryPhone]" value="<?=$r[SecondaryPhone]?>"><br>
</td><td></td></tr>
</table>
<table>
<tr><td colspan="3">
Email Address: 
<input type="text" name="flds[Email]" value="<?=$r[Email]?>" size="40" id="Event">
</td></tr>
</table>
<table>
<tr><td>
Address Line 1: 
<input type="text" name="flds[Address1]" value="<?=$r[Address1]?>">&nbsp;
</td><td>
Address Line 2: 
<input type="text" name="flds[Address2]" value="<?=$r[Address2]?>">
</td></tr>
</table>
<table>
<tr><td>
City: 
<input type="text" name="flds[City]" value="<?=$r[City]?>">
</td><td>
State: 
<input type="text" name="flds[State]" value="<?=$r[State]?>" maxlength="2">
</td><td>
Zip: 
<input type="text" name="flds[Zip]" value="<?=$r[Zip]?>">
</td></tr>
</table>
<table border=0>
<tr>
<td>
  <table border=0>
  <tr>
  <td align="center"><br>
  <img id="pic" src="<?=$imgurl?>" width="200" height="150" alt="" /><br>
  <a class="mod btn btn-primary btn-xs">CHANGE</a>
  <br>
  <input id="hiddenlink" type="hidden" name="flds[ImgURL]" value="<?=$r[ImgURL]?>">
  </td>
  <td>&nbsp;</td>
  <td><b>Biography:</b><br>
  <textarea id=Bio name="flds[Bio]" rows="10" cols="80"><?=$r[Bio]?></textarea>
  </td>
  </tr>
  </table>
</td></tr>
<tr><td>
<b>Notes:</b><br>
<textarea id=Notes name="flds[Notes]" rows="5" cols="100"><?=$r[Notes]?></textarea>
</td</tr></table>

<input type="hidden" name="action" value="update">
<input type="hidden" name="lptr" value="<?=$lptr?>">
<input type="hidden" name="rowid" value="<?=$rowid?>">
<input type="hidden" name="ss" value="<?=$ss?>">
<input type="hidden" name="Active" value="<?=$active?>">
</form>
<div class="hidden-print" align="center">
<button name=reset title="Cancel all changes and restore form to its initial state.">RESET FORM</button>&nbsp;&nbsp;&nbsp;&nbsp;
<button form="F1" class="updb btn btn-success" type="submit">UPDATE LEADER</button></div>
<br><br><br><br>

<!-- Modal definition -->
 <div class="modal fade" id="ldrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Leader Photo Links</h4>
</div>  <!-- modal header -->
<div class="modal-body">

<div id="content" style="overflow-y:scroll; height:200px;">
</div>
</div>  <!-- modal body -->
</div><!-- modal-content -->
</div><!-- modal-dialog -->
</div><!-- modal -->
<!-- end of modal -->

</div> <!-- container -->

</body>
</html>
