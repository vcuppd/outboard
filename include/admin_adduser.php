<?php

// admin_adduser.php
//
// Adduser screen for admin user. Included by the admin.php script when
// the Add button is pressed. This screen is also used to edit existing
// users since the form is the same (just with filled in values).
// 
// 2002-04-11	richardf - Added note about basic auth and passwords
// 2001-06-11	Richard F. Feuerriegel  (richardf@acesag.auburn.edu)

if (! $ob->isAdmin()) { exit; }

$mainscreen = false;  // set to false b/c we are not on the 
		      // main admin screen

// Check to see if the admin user wants to edit an existing user


$editthisuser = getGetValue('editthisuser');
if ($editthisuser > 0) {
  $title = "Editing a user";
  $ob->getDataByID($editthisuser);
  $row = $ob->getRow();
  $userid = $row['userid'];
  $password = $row['password'];
  $name = $row['name'];
  $options = $row['options'];
  if (preg_match("/\<READONLY\>/",$options)) {
    $rochecked = "CHECKED";
  } else {
    $rochecked = "";
  }
  if (preg_match("/\<ADMIN\>/",$options)) {
    $adminchecked = "CHECKED";
  } else {
    $adminchecked = "";
  }
  $submit_button = 
    "<input type=hidden name=rowid value=$editthisuser>"
    . "<input type=submit name=edituser value=\"Update\">";
} else {
  $title = "Adding a user to the outboard";
  $userid = "";
  $name = "";
  $password = "";
  $rochecked = "";
  $adminchecked = "";
  $submit_button = "<input type=submit name=addnewuser value=\"Add This User\">";
}

?>

<table border=0>
  <tr><td colspan=2 align=center><b><?php echo $title ?>:</b></td></tr>
  <tr>
      <td>Username:</td>
      <td><input type=text name=newusername size=20 maxlength=50 
	   value="<?php echo $userid ?>"></td>
  </tr>
  <?php if (! $BasicAuthInUse) { ?>
  <tr>
      <td valign=top>Password:</td>
      <td><input type=password name=newuserpass size=20 maxlength=50
	   value="<?php echo $password ?>"></td>
      </td>
  </tr>
  <?php } else { ?>
      <input type=hidden name=newuserpass value="unused">
  <?php } ?>
  <tr>
      <td>Screen Name:</td>
      <td><input type=text name=newuservisible size=20 maxlength=30
	   value="<?php echo $name ?>"></td>
      </td>
      </td>
  </tr>
  <tr>
  <tr>
      <td valign=top>Options:</td>
      <td>
          <input type=checkbox name=newuserro value="yes" 
	  <?php echo $rochecked ?>> Read Only<br>
          <input type=checkbox name=newuseradmin value="yes" 
	  <?php echo $adminchecked ?>> Admin
      </td>
  </tr>
  <tr>
      <td colspan=2 align=center>
      <?php echo $submit_button ?>
      <input type=submit name=cancel value="Cancel">
      </td>
  </tr>


</table>
