<?php

?>

<script src="js/bootstrap-session-timeout.js"></script> 
<script src="js/jquery-form-restore.js"></script> 
<script>
$(document).ready(function() { 
  $.sessionTimeout({
      title: 'SESSION TIMEOUT ALERT',
      message: '<h3>Your session is about to expire.</h3>',
      keepAlive: false,
      logoutUrl: 'indexsto.php',
      redirUrl: 'indexsto.php',
      warnAfter:  15*60*1000,
      redirAfter: 20*60*1000,
      countdownMessage: 'Time remaining:',
      countdownBar: true,
      showButtons: false
  });
});
</script>

<script>
<!-- Form change variable must be global -->
var chgFlag = 0;

$(document).ready(function() {
// disable all apply update buttons of class updb  
  $('.updb').prop('disabled', true);

// detect and change on form
var $form = $('form');
var formValues = $('form').getFormValues();  // save form in case of reset
var origForm = $form.serialize();   // to save field values on initial load
  
$('form :input').on('change input', function() {
  if ($form.serialize() !== origForm) {         // check for any changes
    chgFlag++;
    $('.updb').prop('disabled', false);    
    $(".updb").css({"background-color": "red", "color":"white"});
    // console.log("chgFlag: "+chgFlag);
    return;
    }
  });

$("[name=reset]").click(function(evt) {
  evt.preventDefault();
  $('form').restoreFormValues(formValues);  // restore form to initial load state
  chgFlag = 0;
  $('.updb').prop('disabled', true);    
  $(".updb").css({"background-color": "green", "color":"white"});
  // console.log("reset chgcount: "+chgFlag);
  // alert("reset clicked");
  });

// check any class of dropdown or clk before exit allowed
$(".dropdown, .clk").click(function() {
    // alert ("navigate button clicked");
    if (chgFlag <= 0) { return true; }
  	var r=confirm("WARNING: All changes made will be LOST.\n\nClick OK to confirm leaving page.\nClick CANCEL to stay on page.");	
  	if (r == true) { chgFlag = 0; return true; }
  		return false;
    });
});

function blink_text() {
    $('.updb').fadeOut(500);
    $('.updb').fadeIn(500);
}
</script>

<div class="hidden-print">
<!-- add padding to top of each page for fixed navbar -->
<style>
body { padding-top: 50px; }
.nav a{
    color: black;
    font-size: 1.25em;
    }     
</style>

<!-- start menu bar -->
<!-- set nav bar fix to top of every page -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation">

<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
</div>
<!-- end hamburger button-->

<!-- collects all tab defs for collapse -->
<div class="collapse navbar-collapse" id="navbar-collapse-1">

<!-- define the menu bar -->
<ul class="nav nav-tabs">
<!-- home page -->  
  <li class="dropdown">
    <a id="dLabel" role="button" class="btn btn-default" 
      href="index.php">Home </a>  
  </li> <!-- dropdown -->

<!-- events -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Events <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="evtlister.php?et=%">List All Events</a></li>
      <li><a href="evtlister.php">Search/List</a></li>
      <li><a href="evtprintlist.php">Print Last List</a></li>
      <li><a href="evtaddevent.php">Add New Event</a></li>
      <li class="divider"></li>      
      <li class="dropdown-submenu">
        <a href="#">Event Reports</a>
        <ul class="dropdown-menu">
          <li><a href="rpteventlisting.php">List of Events</a></li>
          <li><a href="rpteventlistingfull.php">Full Event Listing</a></li>
          <li><a href="rptprogramextract.php">Event Extract</a></li>
          <!-- <li><a href="#">Item</a></li> -->
        </ul>  <!-- dropdown-menu -->
      </li>  <!-- dropdown-submenu -->
      <li class="divider"></li>
      <!-- <li><a href="#">Item</a></li> -->
    </ul>  <!-- dropdown-menu multi-level -->
  </li> <!-- dropdown -->
  
<!-- leaders -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Leaders <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="ldrlister.php">List All Leaders</a></li>
<!--       <li><a href="ldrlister.php">Search Leaders</a></li> -->
      <li><a href="ldraddleader.php">Add New Leader</a></li>
      <li><a href="ldrimagerepomgr.php">Manage Leader Photos</a></li>
      <li class="divider"></li>      
      <li class="dropdown-submenu">
        <a href="#">Leader Reports</a>
        <ul class="dropdown-menu">
          <li><a href="rptleaderinfo.php">Leader Info Report</a></li>
          <li><a href="rptleaderactivity.php">Leader Activity Report</a></li>
          <li><a href="rptleaderemailmerge.php">Leader Mail Merge Report</a></li>
          <!-- <li><a href="#">Item</a></li> -->
        </ul>  <!-- dropdown-menu -->
      </li>  <!-- dropdown-submenu -->
      <li class="divider"></li>
      <!-- <li><a href="#">Item</a></li> -->
    </ul>  <!-- dropdown-menu multi-level -->
  </li>  <!-- dropdown  -->

<!-- venues -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Venues <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="venlister.php?ss=%">List All Venues</a></li>
      <li><a href="venadder.php">Add/Delete Venue(s)</a></li>
      <li class="divider"></li>      
      <li class="dropdown-submenu">
        <a href="#">Venue Reports</a>
        <ul class="dropdown-menu">
          <li><a href="rptvenuelist.php">Venue List`</a></li>
          <li><a href="rptvenueinfo.php">Venue Info Report</a></li>
          <!-- <li><a href="#">Item</a></li> -->
        </ul>  <!-- dropdown-menu -->
      </li>  <!-- dropdown-submenu -->
      <li class="divider"></li>
      <!-- <li><a href="#">Item</a></li> -->
    </ul>  <!-- dropdown-menu multi-level -->
  </li>  <!-- dropdown  -->

<!-- reports -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Reports <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="rptmailerlisting.php">Brochure/Summary Schedule</a></li>
      <li><a href="rptwebsummary.php">Web Site Full Summary Listing</a></li>
      <li><a href="rptstarterlist.php">Starter Report</a></li>
      <li><a href="rptsitesched.php">Site Schedule Report</a></li>
      <li><a href="rptvalidatedb.php">Event Validation Report</a></li>
      <!-- <li><a href="#">Item</a></li> -->
    </ul>  <!-- dropdown-menu multi-level -->
  </li>  <!-- dropdown  -->
  
<!-- Utilities -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Utilities <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="utllistmaint.php">List Maintenance</a></li>
      <li><a href="utllogbrowser.php">Log Browser</a></li>
      <li><a href="utlpagesum.php">Log Page Summary</a></li>
      <li><a href="utlusercount.php">User Page Count</a></li>
      <li><a href="utladmin.php">User Administration</a></li>
      <li><a href="utlresequence.php">Resequence Day Events</a></li>
      <li><a href="utlresetstatus.php">Reset All Event Status</a></li>
      <li><a href="sumextract.php" target="_blank" >SignUp Masters Extract</a></li>
      <li class=divider></li>
      <li><a href="planner.php" target="_blank" class="btn btn-success">Planner Preview</a></li>
      <li><a href="eventleaders.php" target="_blank" class="btn btn-success">Event Leader Preview</a></li>
      <li><a href="familydayleaders.php" target="_blank" class="btn btn-success">Family Day Leader Preview</a></li>
      <!-- <li><a href="#">Item</a></li> -->
    </ul>  <!-- dropdown-menu multi-level -->
  </li>  <!-- dropdown  -->
  
<!-- help -->
  <li class="dropdown">
    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-default" 
    href="#">Help <span class="caret"></span></a>
		<ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
      <li><a href="docs/MBBFUserGuide.pdf" target="_blank">User Guide</a></li>
      <li><a href="docs/DataDictionary.pdf" target="_blank">Data Dictionary</a></li>
      <li><a href="#myModal" data-toggle="modal" data-keyboard="true">About Event Admin</a></li>
    </ul>  <!-- dropdown-menu multi-level -->
  </li>  <!-- dropdown  -->
  
<!-- menu tester -->  
<!--   <li class="dropdown">
    <a id="dLabel" role="button" class="btn btn-default" 
    href="menutester.php">Menu Tester </a>  
  </li> 
 -->
</ul>  <!-- class="nav nav-tabs" -->

</div>  <!-- class="collapse navbar-collapse" -->

</nav>  <!-- class="navbar" -->

<!-- end menu bar -->

<!-- start of modal -->
 <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">About Event Administrator</h4>
</div>  <!-- modal header -->
<div class="modal-body">
<p>Copyright (C) 2017 by Pragmatic Computing, Morro Bay, CA</P
<p>The Event Administrator is a system designed for use by the Morro Bay Bird Festival to organize and optimize the events for their annual winter bird festival event.</p>
<p>This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.</p>
<p>This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.</p>
<p>A copy of this license is available at: <a href="http://www.gnu.org/licenses/gpl.html" target="_blank">http://www.gnu.org/licenses/</a>.</p>
</div>  <!-- modal body -->
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>  <!-- modal-footer -->
</div>  <!-- /.modal-content -->
</div>  <!-- /.modal-dialog -->
</div>  <!-- /.modal -->
<!-- end of modal -->

</div>  <!-- class="hidden-print" -->
