<?php

// loginscreen.php
// 
// The login system for the OutBoard.
//
// 2002-04-11	richardf - changed to always check for basic authentication
// 2001-06-11	Richard F. Feuerriegel (richardf@acesag.auburn.edu)
//	- Initial creation

// Set these so that the program can not be fooled
$username = "";
$password = "";

$message = "<CENTER> &nbsp; </CENTER>";

// Get the username and password from the basic authentication or the
// login screen. $username and $password will be set if basic auth was
// successful.
if (getPostValue('username') != "") {
    $message = "<CENTER><H3>Login Incorrect</H3></CENTER><p>";
} 

if ($BasicAuthInUse) {
    $message = "<CENTER><H3>You are not logged in.<br>"
			."<a href=\"$baseurl\">Click Here</a> "
			."to try again.</H3></CENTER><p>";
}
?>

<HTML>
<HEAD>
<TITLE>OutBoard Login</TITLE>
<?php include("stylesheet.php"); ?>
</HEAD>
<BODY>
&nbsp;<p>
&nbsp;<p>
<FORM name=loginform ACTION="<?php echo $baseurl ?>?noupdate=1" METHOD=post>
<?php echo $message ?>
<?php if (! $BasicAuthInUse) { ?>
<TABLE BORDER=0 align=center CELLPADDING=2 CELLSPACING=0>
<TR><TD class=back>
<TABLE BORDER=0 align=center CELLPADDING=3 CELLSPACING=1>
<TR><TH>OutBoard Login</TH></TR>
<TR><TD align=left>
<table border=0>
<tr>
    <td>Username:</td>
    <td><INPUT TYPE=text NAME=username size=20 maxlength=50></td>
</tr>
<tr>
    <td>Password:</td>
    <td><INPUT TYPE=password NAME=password size=20 maxlength=50></td>
</tr>
<tr><td colspan=2 align=center>
<INPUT TYPE=submit NAME=loginbutton VALUE="Login"></td></tr>
</table><p>
<div align=center>Note: Cookies must be enabled.</div>
</TD></TR>
</TABLE>
</TD></TR>
</TABLE><p>
<?php } // IF ?>
</FORM>
<table border=0 align=center>
<tr>
<td class=small><?php include("about.php"); ?></td>
</tr>
</table>
<script language="JavaScript">
  loginform.username.focus(); 
</script>
</BODY>
</HTML>

<?php exit; ?>
