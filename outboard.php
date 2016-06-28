<?php

// outboard.php
//
// This is the main PHP script that shows the OutBoard interface.
//
// 2007-02-16, richardf - 2.2.4 - added custom time for cookies
// 2006-06-15, richardf - 2.2.3 - added zebra striping option
// 2005-05-16, richardf - 2.2.2 - moved rowcount inside of if() 
// 2005-02-19, richardf - 2.1 - added named anchors to position the page on updates
// 2005-02-15, richardf - 2.0 - see CHANGES file
// 2001-06-08, richardf - 1.3 - see CHANGES file
// 2000-08-30, Richard F. Feuerriegel (richardf@acesag.auburn.edu)
// 	- Initial creation

require_once("lib/OutboardDatabase.php");
require_once("lib/OutboardAuth.php");

include_once("include/char_widths.php"); 
include_once("include/common.php"); 

// Create main objects;
$auth = new OutboardAuth();
$ob   = new OutboardDatabase();

// Set some simple variables used later in the page
$baseurl             = $_SERVER['PHP_SELF'];
$current             = getdate();
$version             = $ob->getConfig('version');
$version_date        = $ob->getConfig('version_date');
$max_visible_length  = $ob->getConfig('max_visible_length');
$cookie_time_seconds = $ob->getConfig('cookie_time_seconds');
$body_bg             = $ob->getConfig('body_bg');
$td_bg               = $ob->getConfig('td_bg');
$td_zebra1           = $ob->getConfig('td_zebra1');
$td_zebra2           = $ob->getConfig('td_zebra2');
$zebra_stripe		 = $ob->getConfig('zebra_stripe');
$td_user_bg          = $ob->getConfig('td_user_bg');
$td_text             = $ob->getConfig('td_text');
$td_lines            = $ob->getConfig('td_lines');
$link_text           = $ob->getConfig('link_text');
$windows_font_family = $ob->getConfig('windows_font_family');
$unix_font_family    = $ob->getConfig('unix_font_family');
$windows_bfs         = $ob->getConfig('windows_bfs');
$unix_bfs            = $ob->getConfig('unix_bfs');
$image_dir           = $ob->getConfig('image_dir');
$change_image        = $ob->getConfig('change_image');
$view_image          = $ob->getConfig('view_image');
$empty_image         = $ob->getConfig('empty_image');
$in_image            = $ob->getConfig('in_image');
$out_image           = $ob->getConfig('out_image');
$dot_image           = $ob->getConfig('dot_image');
$right_arrow         = $ob->getConfig('right_arrow');

// Run the installation script if the config says to
if ($ob->getConfig('installtables')) { include("include/install.php"); }


// Get the session (if there is one)
$session = $auth->getSessionCookie();

if ($ob->getConfig('authtype') == "internal") {
  $BasicAuthInUse = false;
  if ($username = getPostValue('username') and $password = getPostValue('password')) {
    $session = $ob->checkPassword($username,$password);
  }
} else {
  $BasicAuthInUse = true;
  if (! $session) {
    $username = $auth->checkBasic();
    if ($ob->isBoardMember($username)) {
      $ob->setOperatingUser($username);
      $session = $ob->setSession();
    }
  }
}

$auth->setSessionCookie($session,$cookie_time_seconds);
$username = $ob->getSession($session);

// Show the login screen if the user is not authenticated
if (! $username) { 
  $auth->setSessionCookie("",$cookie_time_seconds);
  include("include/loginscreen.php"); 
}

// if 'logout' is set, run the logout functions and go back
// to the login screen.
if (getGetValue('logout')) { 
  $ob->setSession("");
  $auth->setSessionCookie("",$cookie_time_seconds);
  include("include/loginscreen.php");
} 

if (getPostValue('exitadmin')) { 
  // trick the page into noupdate mode
  $_GET['noupdate'] = 1;
} elseif (getGetValue('adminscreen') and $ob->isAdmin() ) { 
  include("include/admin.php"); 
}

// Get the owner of the dot we want to change (might be someone else's dot)
$userid = getGetValue('userid');

// The user wants to move the dot to the Out column
if ($out = getGetValue('out')) { $ob->setDotOut($userid); }

// The user wants to move the dot to the In column
if ($in = getGetValue('in')) { $ob->setDotIn($userid); }

// The user wants to move the dot to the specified "will return by" column. The
// return variable contains the hour in the day that the user will return.
if ($return = getGetValue('return')) { $ob->setDotTime($userid,$return); }

// The user wants to change the remarks. We have to use isset() here first 
// to allow for empty remarks.
if (isset($_GET['remarks'])) { 
  $remarks = getGetValue('remarks');
  $ob->setRemarks($userid,$remarks); 
}


// Appropriately set the update flag. 
if (getGetValue('noupdate')) { 
  $update = 0; 
  if ($current['hours'] >= 6 && $current['hours'] <= 18 ) { 
    $update_msec = $ob->getConfig('reload_sec') * 1000;
  } else {
    // Set the update rate to the "night rate" if between 6:00pm and 6:00am
    $update_msec = $ob->getConfig('night_sec') * 1000;
  }
} else { 
  $update = 1; 
  $update_msec = $ob->getConfig('update_sec') * 1000;
}


?>

<HTML>
<HEAD>

<SCRIPT Language="JavaScript">
  function openWindow( window_name, url, width, height ) {
    locX = (screen.width / 2) - (width / 2);
    locY = (screen.height / 2) - (height / 2);
    window_name = window.open(url, window_name,
      "dependent=yes,resizable=yes,scrollbars=yes,screenX=" + locX 
       + ",screenY=" + locY + ",width=" + width + ",height=" + height);
    window_name.focus();
  } 
  function myReload() {
    self.location = "<?php echo $baseurl ?>?noupdate=1";
  }
  t = setTimeout("myReload()",<?php echo $update_msec ?>);
</SCRIPT>

<?php if ($launch = getGetValue('launch')) { ?>
  <Script Language="JavaScript"> window.focus(); </Script>
<?php } ?>

<TITLE>OutBoard: <?php echo $ob->getConfig('board_title') ?></TITLE>
<?php include("include/stylesheet.php"); ?>
</HEAD>
<BODY>

<Script language="JavaScript1.2">
  function change_remark(remark,userid) {
    var newremark = prompt("Enter your remarks below:",remark);
    if (newremark != null) {
      self.location="<?php echo $baseurl ?>?remarks=" 
		    + escape(newremark) + "&userid=" +userid + "#<?php echo $userid ?>";
    }
  }
</Script>

<TABLE BORDER=0 WIDTH=100%>
  <TR>
  <TD class=header><?php echo $ob->getConfig('board_title') ?></TD>
  <TD class=headernb><?php echo date($ob->getConfig('date_format')) ?></TD>
  <TD class=header align=right>
  <?php if($ob->isReadonly()) { $readonly = true; } else { $readonly = false; }?>
  <?php if(getGetValue('noupdate')) $update = 0;?>
  <?php if (! $update && ! $readonly) { ?>
    <a href="<?php echo $baseurl ?>?update=1#<?php echo $username ?>"><img src=<?php echo "$image_dir/$change_image" ?> ALT="Switch to update mode" TITLE="Switch to update mode" BORDER=0></a> 
  <?php } elseif (! $readonly) { ?>
    <a href="<?php echo $baseurl ?>?noupdate=1"><img src=<?php echo "$image_dir/$view_image" ?> ALT="Switch to view only mode" TITLE="Switch to view only mode" BORDER=0></a> 
  <?php } else { echo "&nbsp;"; } ?>
  </TD>
  </TR>
</TABLE>


<TABLE BORDER=0 WIDTH=100% ALIGN=CENTER>
<TR><TD CLASS=back>
<TABLE BORDER=0 WIDTH=100% ALIGN=CENTER>
<TR><TH></TH><TH></TH><TH colspan=10>Will return by this time:</TH><TH></TH><TH></TH></TR>

<?php $header = "<TR><TH>Name</TH><TH>In</TH>
<TH>8</TH><TH>9</TH><TH>10</TH><TH>11</TH><TH>12</TH><TH>1</TH><TH>2</TH><TH>3</TH>
<TH>4</TH><TH>5</TH><TH>Out</TH><TH>Remarks</TH></TR>";

//echo $header;
?>

<?php
// Get the latest outboard information from the database
$ob->getData();

$rowcount = 0;
$zebra = 2;
$username = urlencode($username);

while($row = $ob->getRow()) {
  $isChangeable = $ob->isChangeable($row['userid']);
  $row['userid'] = urlencode($row['userid']); 
  if (! preg_match("/\<READONLY\>/",$row['options'])) {
     $datetime = getdate($row['back']);
     if ($row['last_change'] != "") { 
       list($uname,$ip) = explode(",",$row['last_change']);
       $lastup = "Last updated by $uname from $ip on " .  $row['timestamp'] . ""; 
       $alt = "ALT=\"$lastup\" TITLE=\"$lastup\""; 
     } else {
       $alt = "";
     }
     $in = "<img src=$image_dir/$in_image $alt>"; 
     if ($datetime['year'] > $current['year']) { 
       $out = "<img src=$image_dir/$out_image $alt>"; 
       if ($update && $isChangeable) {
	     $in= "<a href=\"$baseurl?in=1&userid=".$row['userid']."#".$row['userid']."\">"
	         ."<img src=$image_dir/$empty_image BORDER=0></a>"; 
       } else {
	     $in= "<img src=$image_dir/$empty_image>"; 
       }
     } else {
       if ($update && $isChangeable) {
	     $out= "<a href=\"$baseurl?out=1&userid=".$row['userid']."#".$row['userid']."\">"
		      ."<img src=$image_dir/$empty_image BORDER=0></a>"; 
       } else {
	     $out= "<img src=$image_dir/$empty_image>"; 
       }
     }
	 for ($i = 8; $i <= 17; $i++) {
		 if ( $datetime['hours'] == $i ) { 
		   $back[$i] = "<img src=$image_dir/$dot_image $alt>"; 
		   if ($update && $isChangeable) {
			 $in= "<a href=\"$baseurl?in=1&userid=".$row['userid']."#".$row['userid']."\">"
			 ."<img src=$image_dir/$empty_image BORDER=0></a>"; 
		   } else {
			 $in= "<img src=$image_dir/$empty_image>"; 
		   }
		 } else {
		   if ($update && $isChangeable) {
			 $back[$i] = "<a href=\"$baseurl?return=$i&userid=".$row['userid']."#".$row['userid']."\">"
				."<img src=$image_dir/$empty_image BORDER=0></a>"; 
		   } else {
			 $back[$i] = "<img src=$image_dir/$empty_image>"; 
		   }
		 }
	 }
	 if ($ob->getConfig('zebra_stripe') != 0) {
		if ($rowcount % $ob->getConfig('zebra_stripe') == 0) { 
			if ($zebra == 1) { $zebra = 2; } else { $zebra = 1; }
		}
		$user_bg = "class=zebra".$zebra;
	 } else {
		$user_bg = ""; 
	 }
	 if ($row['userid'] == $username && $update && $isChangeable) { 
		 $user_bg = "class=user"; 
	 }
     if ($rowcount % $ob->getConfig('reprint_header') == 0) { echo $header; }
     echo "<TR class=norm>";
     echo "<TD WIDTH=15% $user_bg><A class=\"nobr\" name=\"".$row['userid']."\">".$row['name']."</A></TD>";
     echo "<TD WIDTH=1% $user_bg>$in</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['8']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['9']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['10']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['11']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['12']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['13']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['14']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['15']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['16']."</TD>";
     echo "<TD WIDTH=1% $user_bg>".$back['17']."</TD>";
     echo "<TD WIDTH=1% $user_bg>$out</TD>";
     if ($row['remarks'] == "") { 
       if ($update) {
	     $print_remarks = "&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; "
			 ."&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; "
			 ."&nbsp; &nbsp; &nbsp; &nbsp;"; 
       } else {
	     $print_remarks = "&nbsp;";
       }
     } else { 
       $visible = trim_visible($row['remarks'],$max_visible_length);
       if ($visible != $row['remarks']) {
	     $rem = $row['remarks'];
         $alt = "ALT=\"$rem\" TITLE=\"$rem\""; 
	     $print_remarks = htmlspecialchars($visible)
	                  . "<img src=$image_dir/$right_arrow BORDER=0 $alt>";
       } else {
	     $print_remarks = htmlspecialchars($visible);
       }
     }
     if ($update && $isChangeable) {
       echo "<TD WIDTH=\"55%\" $user_bg><a href=\"javascript:this.change_remark('" 
	    . addslashes(htmlspecialchars($row['remarks'])) 
	    . "','".$row['userid']."')\">$print_remarks</a></TD>";
     } else {
       echo "<TD WIDTH=\"55%\" $user_bg>$print_remarks</TD>";
     }
     echo "</TR>\n";
	 $rowcount++; 
   } // end if
} // end while

?>
</TABLE>
</TD></TR>
</TABLE>


<TABLE BORDER=0 WIDTH="100%" CELLPADDING=1 CELLSPACING=0>
  <TR>

  <?php if (! $BasicAuthInUse) { ?> 
    <TD class=small align=left width="1%">
      <a href="<?php echo $baseurl ?>?logout=1">[Logout]</a>
    </TD>
  <?php } ?>

  <?php if ($sched_url = $ob->getConfig('schedule_url')) { ?>
    <TD class=small align=center>
     <a href="javascript:void(0)" 
	onClick="openWindow('scheduleWindow','<?php echo $sched_url ?>',550,600)"><?php echo $ob->getConfig('schedule_name'); ?></a>
    </TD>
  <?php } ?>

  <TD class=small align=center>
  <?php include("include/about.php"); ?>
  </TD>

  <TD class=small align=left width="1%">
      <a href="timeclock.php" target=_blank>[Report]</a>
  </TD>

  <?php if ($ob->isAdmin()) { ?>
  <TD class=small align=left width="1%">
      <a href="<?php echo $baseurl ?>?adminscreen=1">[Admin]</a>
  </TD>
  <?php } ?>

  </TR>
</TABLE>

</BODY>
</HTML>
