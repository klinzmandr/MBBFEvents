<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; -->
<!-- any other head content must come *after* these tags -->
<title>Image Repository</title>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css " rel="stylesheet" media="all">
<link href="css/fileinput.min.css " rel="stylesheet" media="all">
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
<script src="js/fileinput.min.js"></script>

<div class="container">
<?php
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

// include 'Incls/vardump.inc.php';
include 'Incls/datautils.inc.php';
include 'Incls/mainmenu.inc.php';

// Process listing based on selected criteria
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";

if (isset($_REQUEST['delete'])) {
	$fn = '../mbbfLeaderPics/'.$_REQUEST['delete'];
	if (unlink($fn)) { 
	  echo '
	  <div class="ERR"><h4 style="color: red; ">Deleted photo '.$fn.'</h4></div>';
	  addlogentry("Deleted photo " . $fn); 
	  }
	else {
	  echo '
	  <div class=\"ERR\"><h4 style=\"color: red; \">Deleted file '.$fn.' FAILED!</h4></div>';
	  addlogentry("Deleted photo ".$fn." FAILED");
    }
  }
  
if (isset($_REQUEST['rename'])) {
	$oldshort = $_REQUEST['rename'];
	$old = '../mbbfLeaderPics/'.$_REQUEST['rename'];
	$newshort = $_REQUEST['newname'];
	$new = '../mbbfLeaderPics/'.$_REQUEST['newname'];
	if (file_exists($new)) {
	  echo '
	  <div class="ERR"><h4 style="color: red; ">
 		  Rename request failed. Name already exits</h4></div>'; 
 		addlogentry("Rename of ".$oldshort." FAILED. Name already existed"); 
		}
	else {
		if (rename($old, $new)) {
		  echo "
		  <div class=\"ERR\"><h4 style=\"color: red; \">
		  Rename request completed.</h4></div>";
		  addlogentry("Renamed ".$oldshort." to ".$newshort." successfully");  
			}
		else {
		  echo '
		  <div class="ERR"><h4 style="color: red; ">Rename request FAILED!<br>
			New name provided already exists OR path name invalid</h4></div>';
			addlogentry("Rename of ".$oldshort." FAILED");
			}
    }
  }

?>
<script>
$(document).ready(function() {
  $(".ERR").fadeOut(5000);
  $("[id^=sum]").hide();
  $("#xf").hide();
  
  $(".confirm").click(function() {
    var r=confirm("This action is irreversable.\n\n Confirm action by clicking OK: ");
    return r;    // OK = true, Cancel = false
  	});

  $("#addf").click (function() {
    $("#xf").toggle();
    });
    
// does case insensitive search in 'filterbtn1'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });

$("#filterbtn2").click(function() {
  $("#filter").val("");
  $('tr').show();
  chgFlag = 0;
  });
  
// $("#filterbtn1").click(function() {
$("#filter").keyup(function() {
  var filter = $("#filter").val();
  if (filter.length) {
    // alert("filter button clicked:" + filter);
    $('tr').hide().filter(':containsNC('+filter+')').show();
    $("#head").show();
    chgFlag = 0;
    return;
    }
  $('tr').show();
  chgFlag = 0;
  });

});

function tog(f) {
  var id = "#" + f;
  $(id).toggle();
  }

function getfld(OName) {
var inval = OName;
//	if prompt dialog is canceled it exits the script
var val = prompt("Please enter a NEW name (including the file extension if needed):",inval);
if (val.length > 0) {
		document.getElementById("HF1").value = inval;
		document.getElementById("HF2").value = val;
		document.forms["NameForm"].submit();
  	return true;
	}
alert("Rename action cancelled");
return false;
}
</script>
<!-- define form to submit WITHOUT a submit field defined -->
<form method="post" name="NameForm" action="ldrimagerepomgr.php">
<input type="hidden" id="HF1" name="rename" value="">
<input type="hidden" id="HF2" name="newname" value="">
</form>

<h3>Leader Image Repository
<span onclick='tog("sum-1")'><span title="Help" class="hidden-print glyphicon glyphicon-question-sign" style="color: blue; font-size: 20px"></span>
</span>
</h3>
<div id="sum-1">
<p>This image repository contains photos of all leaders that are to be included in the web site leader review, a part of the event planner.  A link in the planner display for an event will allow access to the picture and related biography info for the leader of that event.</p>
<p><b>Notes:</b></p>
<ol>
  <li>The &apos;filter&apos; function allows for selection of a group of photos based on a character string found in the photo name.</li>
	<li>Photos should be &apos;head and shoulders&apos; photos in &apos;landscape&apos; orientation.</li>
	<li>Image editting should be done prior to uploading the image.</li>
	<li>All photos will be resized to fit the layout as needed but will always be in a landscape orientation.</li>
	<li>The name of the photo should contain the complete last name of the leader.  It is recommended that the name consist of the first name followed by a space then the last name both starting with capital letters for readability. For example: leader John Doe would have a photo named &apos;John Doe.jpg&apos;</li>
	<li>What ever the naming convention, it should be consistant for all.</li>
	<li>The format of the photo image can be jpg, jpeg, pgn or tif formats</li>
	<li>The total file size of the photo should be in the range of 50-70 KBytes - never more than 100 KBytes.</li>
	<li>Repository functions to &apos;upload&apos;, &apos;delete&apos; and &apos;rename&apos; photo images is provided.</li>
	<li>A new photo&apos;s name CANNOT be duplicated.  Delete the old name or rename the it if multiple photos of a single leader is needed.</li>
	<li>Only one photo of a leader will be &apos;active&apos; at a time.</li>
	<li>Photos are assigned to a leader in the leader profile.</li>
</ol>
</div>

<?php
if (!count($_FILES)) echo ' 
<button class="btn btn-primary btn-xs" id="addf">Add File(s)</button>
<div id="xf" class="form-group">
<form class="form-inline" role="form" action="ldrimagerepomgr.php" method="post" enctype="multipart/form-data">
<b>Select file(s):</b><input type="file" name="files[]" class="file" id="file" multiple>
</form>
</div>   <!-- form-group -->
<br>
Filter: <input id=filter autofocus> <button id=filterbtn2>Show All</button>
<br><br>';
// process uploaded files, if any
$msg = "";     //initiate the progress message
if (count($_FILES)) {
  for ($i = 0; $i<count($_FILES["files"]["name"]); $i++) {
    $filex = '../mbbfLeaderPics/' . $_FILES["files"]["name"][$i];
    $filen = $_FILES["files"]["name"][$i];
    if (file_exists($filex)) {
      $msg .= "<b>ERROR: </b>File $filen already exists.  Upload ignored!<br>";
      continue;
      }
    if ($_FILES["files"]["error"][$i] > 0) {
    	$msg .= "Error " . $_FILES["files"]["error"][$i] . "on upload of $filex<br>";
    	continue;
    	}
    $dest = '../mbbfLeaderPics/' . $_FILES["files"]["name"][$i];
  //  echo "dest: $dest<br>";
  //  echo "i: $i<br>, name: " . $_FILES["files"]["name"][$i] . '<br>';
  //  echo "tmp_name: " . $_FILES["files"]["tmp_name"][$i] . "<br />";
  //  echo "Size: " . ($_FILES["files"]["size"][$i] / 1024) . " Kb<br>=====<br>";
   	if (move_uploaded_file($_FILES["files"]["tmp_name"][$i], $dest)) {
      $msg .= "File# ".($i+1)." ($filen) uploaded successfully<br>"; 	  
   	  }
   	else { $msg .= "Move of $filen to $dest failed<br>"; }
    } // file count
  } // if file count > 0

// if (strlen($msg) > 0) echo "<div class=\"ERR\">$msg</div>"; 
if (strlen($msg) > 0) { 
  echo "<h3>$msg</h3><br>";
  addlogentry($msg);
  echo '<a class="btn btn-primary" href="ldrimagerepomgr.php">Continue</a>';
  exit;
} 

$pics = scandir("../mbbfLeaderPics");
echo '
<style> .x { font-size: 25px; } </style>
<table class="table" border=1>';
foreach ($pics as $p) {
  if (substr($p,0,1) == '.') continue;  // ignore dot files
  echo '
  <tr><td width="200" height="150">
  <img src="../mbbfLeaderPics/'.$p.'" width="200" height="150" alt=""></td> 
  <td><br><a class="x" target="_blank" href="../mbbfLeaderPics/'.$p.'">'.$p.'</a>
  <br><br>
  <a class="confirm btn btn-danger btn-xs" href="ldrimagerepomgr.php?delete='.$p.'">DELETE</a>
  &nbsp;&nbsp;
  
  <a class="btn btn-success btn-xs" onclick=\'return getfld("'.$p.'")\' href="#">RENAME</a>
  </td></tr>';
  }
echo '</table>';


?>
</div> <!-- container -->
</body>
</html>