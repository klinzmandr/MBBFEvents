<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add New Venue</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
//$currsites = $_REQUEST['currsites'];
$currsites = $_SESSION['sitenames'];
$jsvals = $_REQUEST['jsvals'];
$vencode = $_REQUEST['vencode'];
$venname = $_REQUEST['venname'];

if ($action == 'delete') {
  $delarray = explode(",",$jsvals);
  // echo '<pre>delarray '; print_r($delarray); echo '</pre>';
  // echo '<pre>currsites before '; print_r($currsites); echo '</pre>';
  
  foreach ($delarray as $d) {
    unset($currsites[$d]);
    $sql = "DELETE FROM `venues` WHERE `VenCode` = '$d'";
    $res = doSQLsubmitted($sql);
    // echo "sql: $sql<br>result: $res<br>";
    }

  echo "<ul><h3>Venue delete(s) completed.</h3>
  Venues deleted:<br>"; 
  echo '<pre>'; print_r($delarray); echo '</pre>
  <a href="venadder.php" class="btn btn-primary">CONTINUE</a></ul>';
  unset($_SESSION['sitenames']);
  exit;
  }

if ($action == 'addnew') {

// add new db entry
  $updarray['VenCode'] = $vencode;
  $updarray['VenName'] = $venname;
  sqlinsert('venues', $updarray);
  
  echo "<br><br><ul>
  <h3>Addition of site $venname ($vencode) completed.</h3>
  List all venues to find and complete data entry for $venname<br>
  <br><br>";
  echo '<a href="venadder.php" class="btn btn-primary">CONTINUE</a></ul>'; 
  unset($_SESSION['sitenames']);
  exit;
  
  }

// read current venue db table for venue codes and names
$venlistarray = readvenlistarray('Site');
// echo '<pre>listxx '; print_r($venlistarray); echo '</pre>';

foreach ($venlistarray as $l) {
  preg_match("/^.*[\"\'](.*):(.*)[\"\']/i", $l, $matches);
  if (!strlen($matches[2])) continue;
  $sitecodes[] = $matches[2];
  $sitenames[$matches[2]] = $matches[1];
  }
// echo '<pre>sitecode '; print_r($sitecodes); echo '</pre>';
// echo '<pre>sitenames '; print_r($sitenames); echo '</pre>';

// save for delete or add processing use
$_SESSION['sitenames'] = $sitenames;

$jscodes = "['" . implode("', '", $sitecodes) . "']";
// echo '<pre>jscodes '; print_r($jscodes); echo '</pre>';
?>
<script>
var codes = <?=$jscodes?>;
var delarray = new Array();
$("document").ready(function() {
  $("#help").hide();
  
  $("#helpclk").click(function() {
    $("#help").toggle();
  });

  $("#code").blur(function() {
    var inp = $("#code").val();
    inp = inp.toUpperCase();
    if (codes.includes(inp)) {
      alert("Code "+inp+" already defined");
      $("#code").val('');
      return;
      }
    $("#code").val(inp);
  });
  $("#name").blur(function() {
    var inpc = $("#name").val();
    var newinpc = inpc.replace(/[\(\)\.\-\'\"\&\*]/g,'');
    $("#name").val(newinpc);
  });
  $("tr").click(function() {
    var id = $(this).attr("id");
    if (delarray.includes(id)) {
      $("#"+id).find("span").removeClass("glyphicon glyphicon-checked").addClass("glyphicon glyphicon-unchecked");
      var idx = delarray.indexOf(id);
      delarray.splice(idx,1);
      $("#jsvals").val(delarray);
      chgFlag -= 1;
      // console.log(idx, delarray);
      return;
      }
    delarray.push(id);
    $("#"+id).find("span").removeClass("glyphicon glyphicon-unchecked").addClass("glyphicon glyphicon-check");
    $("#jsvals").val(delarray);
    chgFlag += 1;
    // console.log(delarray);
  });
    
});

function chkform() {
  var sc = $("#code").val().length;
  var sn = $("#name").val().length;
  if ((!sc) || (!sn)) {
    alert ("missing parameter");
    return false;
    }
  return true;
  }  

function chkdelform() {
  if (delarray.length == 0) return false;
  if (confirm("This action can not be reversed.\n\nPlease confirm deletion by clicking OK\nor CANCEL to ignore.")) { return true; }
  return false;
  }
</script>
<ul>
<h3>Add new site/venue code&nbsp;&nbsp;&nbsp;<span id="helpclk" title="Help" class="glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span></h3>
<div id="help">
<p>The process of adding a new venue/site starts with creating a new, UNIQUE code to identify it.  When this is done the new code and associated name are added to an event&apos;s venue/site selection list and a new venue/site record is added to the database for venue/site details.  A list of the existing site/venue codes and their associated names are listed below for review and potential deletion.</p>
<p>There is no ability to rename or clone a venue/site&apos;s information into another.  If a venue/site needs to be renamed, then a new item must be created and the information re-entered before deletion of the old record.</p>
<p>There might be a case when multiple events use the same general site/venue but unique instructions on attendance, entry, location, etc. is needed.  In this instance it is recommended that the unique code be comprised of the existing code appended by a number or letter.  Then a new record with this new code can be created with the unique instructions required and associated with the appropriate event.</p>
<p>Doing this will allow the site/venue codes to be grouped and easier to manage.</p>
</div>   <!-- help -->
<form action="venadder.php" method="post" onsubmit="return chkform()">
<input id="code" type="text" name="vencode" value="" placeholder="New Site Code">
<input id="name" type="text" name="venname" value="" placeholder="Site Name">
<input type="hidden" name="action" value="addnew">

<?php
foreach ($sitenames as $k => $v) {
  echo "<input type=\"hidden\" name=\"currsites[$k]\" value=\"$v\" >";
  }
?>

 <input type="submit" name="submit" value="Create new venue/site">
</form>

<h3>Existing Codes and Site/Venue Names</h3>
<ul>
<form action="venadder.php" method="post" onsubmit="return chkdelform()">

<?php
foreach ($sitenames as $k => $v) {
  echo "<input type=\"hidden\" name=\"currsites[$k]\" value=\"$v\" >";
  }
?>

<input id="jsvals" type="hidden" name="jsvals" value="" >
<input type="hidden" name="action" value="delete" >
<input type="submit" name="submit" value="Delete checked venues/sites">
</form>
<table border=0>
<tr><th>Del</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Code</th><th>&nbsp;&nbsp;&nbsp;&nbsp;</th><th>Name</th></tr>

<?php
ksort($sitenames);
foreach ($sitenames as $k => $v) {
  echo "
<tr id='$k'>
<td style='cursor: pointer;'><span title='DELETE?' class='glyphicon glyphicon-unchecked' style='color: blue; font-size: 20px'></span></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>$k</td<>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>$v</td>
</tr>";
  }
?>
</table>===== END REPORT =====<br><br><br>
</ul>
</ul>
</body>
</html>