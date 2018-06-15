<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Update Lister</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/bs3dropdownsubmenus.css" rel="stylesheet">
</head>
<body>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/js.cookie.js" type="text/javascript"></script>

<script>
var inp = '';

// does case insensitive search in 'btnALL'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });

$(document).ready(function() {
  $("tr").show();
  $("#head").show();
  $("#help").hide();
  inp = Cookies.get("filter");
  if (!inp) inp = '';
  $("#inp").val(inp);
  if (inp.length > 0) 
    $('tr').hide().filter(':containsNC('+inp+')').show();
  $("#head").show();
  
$("#helpclk").click(function() {
  $("#help").toggle();
  });
});

$(function(){
  $('#inp').keyup(function() {
    var v = $("#inp").val();
    if (!v) v = '';
    console.log(v);
    if (v.length) 
      $('tr').hide().filter(':containsNC('+v+')').show();
    $("#head").show();
    chgFlag = 0;
    Cookies.set("filter", v);
    });

    $('#btnALL').click(function() {
      $('tr').show();
      $('#inp').val('');
      chgFlag = 0;
      Cookies.remove('filter');     
      });
 });
</script>


<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

//include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
// include 'Incls/listutils.inc.php';
include 'Incls/mainmenu.inc.php';

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

// process delete action requested
if ($action == 'delete') {
  $rowid = isset($_REQUEST['rowid']) ? $_REQUEST['rowid'] : '';
	//echo "delete $rowid requested<br>";
	$sql = "DELETE FROM `leaders` WHERE `RowID` = '$rowid';";
	$rc = doSQLsubmitted($sql);		// returns affected_rows for delete
	echo '
<script>
$(document).ready(function() {
  $("#X").fadeOut(3000);
});
</script>';

	if ($rc > 0) 
		echo '<h3 style="color: red; " id="X">Leader deleted.</h3><br>';
	else
		echo '<h3 style="color: red; " id="X">Leader delete failed.</h3><br><br>';
	}

// Process listing based on selected criteria
$sql = 'SELECT * FROM `leaders` WHERE 1 ORDER BY `LastName` ASC, `FirstName` ASC';
//echo "<br>sql: $sql<br>";
$res = doSQLsubmitted($sql);
$rc = $res->num_rows;

echo '
<h2>Leader List</h2>
Leaders count: '.$rc.'<br>
<input type="text" id="inp" placeholder="FILTER" autofocus>
&nbsp;
<button id="btnALL">Show All</button>
<table border=1 class="table table-condensed table-hover">
<tr id="head" class="hidden-print"><th>Active?</th><th>Name</th><th>PriPhone</th><th>SecPhone</th><th>Email</th></tr>
';
$lnavarray = array(); $lvar = array(); $lptr = 0;
while ($r = $res->fetch_assoc()) {
  //if ($r['FirstName'] == '**New**') continue;
  //echo '<pre> full record '; print_r($r); echo '</pre>';
  $lptr = $r[RowID];
  echo "<tr onclick=\"window.location='ldrupdate.php?rowid=$lptr'\" style='cursor: pointer;'>";
  echo '
<td>'.$r[Active].'</font></td>
<td>'.$r[FirstName].'&nbsp;'.$r[LastName].'</font></td>
<td>'.$r[PrimaryPhone].'</font></td>
<td>'.$r[SecondaryPhone].'</font></td>
<td>'.$r[Email].'</font></td>
</tr>
';
  }
?>
</table>===== END REPORT =====<br>
</div> <!-- contianer -->
</body>
</html>