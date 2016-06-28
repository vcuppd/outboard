<?php

// admin.php
// 
// The Administration screen for the admin user. This script is automatically
// included by the outboard.php script if the admin user is logged in.
//
// 2005-02-16	Richard F. Feuerriegel (richardf@acesag.auburn.edu)
//	- Changed to work with OutBoard 2.0
// 2001-06-11	Richard F. Feuerriegel (richardf@acesag.auburn.edu)
//	- Initial creation

if (! $ob->isAdmin()) { exit; }

$message = "";   // Stores status messages of admin actions

// Does user want to add or edit a user?
if (getPostValue('addnewuser') or getPostValue('edituser')) {
    $newusername = getPostValue('newusername');
    $newuserpass = getPostValue('newuserpass');
    $newuservisible = getPostValue('newuservisible');
    $rowid = getPostValue('rowid');
    $options = "";
    if (getPostValue('newuserro') == "yes") { $options .= "<READONLY>"; }
    if (getPostValue('newuseradmin') == "yes") { $options .= "<ADMIN>"; }
    $result = 0;
    if ($newusername != "" and $newuserpass != "" and $newuservisible != "") {
      if (getPostValue('edituser')) {
	$result = $ob->saveUser($rowid,$newusername,$newuserpass,$newuservisible,$options);
      } else {
	// No rowid, so it will make a new user
	$result = $ob->saveUser("",$newusername,$newuserpass,$newuservisible,$options);
      }
    }
    if (! $result) {
      $message = "Error: user not added";
    } else {
      $message = "Success: user \"$newusername\" added/updated.";
    }
} elseif ($deletethisuser = getGetValue('deletethisuser')) {
  $result = $ob->deleteUser($deletethisuser);
  if (! $result) {
    $message = "Error: user not deleted";
  } else {
    $message = "Success: user deleted.";
  }
}

?>

<HTML>
<HEAD>
<TITLE>OutBoard Administration</TITLE>
<?php include("include/stylesheet.php"); ?>
</HEAD>
<BODY>
<FORM ACTION="<?php echo $baseurl ?>?adminscreen=1" METHOD=post>
<INPUT TYPE=HIDDEN NAME=adminscreen VALUE="1">
<TABLE BORDER=0 align=center CELLPADDING=2 CELLSPACING=0>
<TR><TD class=back>
<TABLE BORDER=0 align=center CELLPADDING=3 CELLSPACING=1>
<TR><TH>OutBoard Administration</TH></TR>
<TR class=norm><TD align=center>

<?php 
  if ($message != "") { echo "<br>$message<br><hr>"; }
  $mainscreen = true;     // Other screens change this
  if ($adduser = getPostValue('adduser'))      { include("include/admin_adduser.php");   }
  if ($editusers = getPostValue('editusers'))    { include("include/admin_editusers.php"); } 
  if ($editthisuser = getGetValue('editthisuser')) { include("include/admin_adduser.php"); }

  // Show correct buttons depending on the current screen
  if (! $mainscreen) { echo "<hr>"; } else { echo "<br>"; }
  if (! $adduser) {
    echo "<INPUT TYPE=submit NAME=adduser VALUE=\"Add A User\"><p>\n";
  }
  if (! $editusers and ! $editthisuser) {
    echo "<INPUT TYPE=submit NAME=editusers VALUE=\"Edit Users\"><p>\n";
  }
?>

<INPUT TYPE=submit NAME=exitadmin VALUE="Return to OutBoard"><p>

</TD></TR>

</TD></TR>
</TABLE>
</TD></TR>
</TABLE><p>
</FORM>
<table border=0 align=center>
<tr>
<td class=small><?php include("include/about.php"); ?></td>
</tr>
</table>
</BODY>
</HTML>

<?php
   exit; // exit since this is an include and we don't want to
         // go any farther in the calling script
?>
