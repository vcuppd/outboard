<?php

// install.php
// 
// If the "installtables" variable is set to true, this file is
// included in the outboard.php file to create the necessary tables.
//
// 2005-03-06	richardf - Removed need for register_globals being on
// 2005-02-16	richardf - changed to work with OutBoard 2.0
// 2002-04-11	richardf - corrected spelling error
// 2001-06-08	Richard F. Feuerriegel (richardf@acesag.auburn.edu)
//	- Initial creation

if (! isset($ob)) { exit; }

if (! $ob->getConfig('installtables')) { exit; }

if (! function_exists("pg_connect")) {
	echo "Error: The MySQL libraries are not available in your installation of PHP.";
	exit;
}

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
error_reporting(E_ALL);
ini_set("display_errors",1);
ini_set("display_startup_errors",1);

// Set up some variables for east of use later;
$db       = $ob->getConfig('db');
$dbuser   = $ob->getConfig('dbuser');
$dbhost   = $ob->getConfig('dbhost');
$dbpass   = $ob->getConfig('dbpass');
$table    = $ob->getConfig('table');
$logtable = $ob->getConfig('logtable');

$submit    = getPostValue('submit');
$adminuser = getPostValue('adminuser');
$adminpass = getPostValue('adminpass');
$adminname = getPostValue('adminname');

?>

<HTML>
<HEAD>
<TITLE>OutBoard Installation</TITLE>
<?php include("include/stylesheet.php"); ?>
</HEAD>
<BODY>
<TABLE BORDER=0 align=center CELLPADDING=2 CELLSPACING=0>
<TR><TD class=back>
<TABLE BORDER=0 align=center CELLPADDING=3 CELLSPACING=1>
<TR><TH>OutBoard Installation</TH></TR>
<TR><TD align=left>

<?php
$error = false;
if ($submit) {   // Run this if the user pressed the Install button
  // Install the tables
  include("include/create_tables.php");
  if ($adminpass == "" or $adminuser == "" or $adminname == "") { 
    $error = true; 
    echo "Please enter the necessary information for the <b>admin</b> user.<p>";
    echo "<a href=\"$baseurl\" class=blue>Click Here</a> to go back.<p>";
  }

  if (! $error) {
  echo "---------------------------------------------------<br>";
  echo "Connecting to the database ($db)... ";

  $dbh = @pg_connect("host=" . $ob->getConfig('dbhost')
                                . " user=" . $ob->getConfig('dbuser')
                                . " dbname=" . $ob->getConfig('db')
                                . " password=" . $ob->getConfig('dbpass')
		);


  if (!$dbh) { 
    echo "<br><b>There was an error:</b><br><hr>";
    echo "<hr><br>";
    echo "If you have not already created the database, do so now by issuing the following ";
    echo "command at a shell prompt:<p>";
    echo "<b>mysqladmin -u USERNAME --password=PASSWORD create $db</b><p>";
    echo "USERNAME and PASSWORD above need to be valid on your MySQL server.<p>";
    $error = true;
  } else {
    echo "Done.<p>";
    echo "---------------------------------------------------<br>";
    echo "Creating <i>$table</i> table... ";
    $result = @pg_query($dbh, $outboard_table);
    if ($result) {
      echo "Done.<p>";
      echo "---------------------------------------------------<br>";
      echo "Creating <i>$logtable</i> table... ";
      $result = @pg_query($dbh, $outboard_log_table);
      if ($result) {
        echo "Done.<p>";
        echo "---------------------------------------------------<br>";
        echo "Adding admin user to <i>$table</i> table... ";
	$adminpass = addslashes($adminpass);
	$adminuser = addslashes($adminuser);
	$adminname = addslashes($adminname);
        $result = pg_query($dbh,
	   "INSERT into $table (userid,password,name,options) "
	  ."VALUES ('$adminuser',md5('$adminpass'),'$adminname','<ADMIN>')");
        echo "Done.<p>";
	echo "<b><u>IMPORTANT</u></b>: Now, please edit the <b>config.php</b> ";
	echo "file and change ";
	echo "the <b>installtables</b> variable to \"false\".<p>";
	echo "Then, login to the OutBoard:<p>";
        echo "<a href=\"$baseurl\" class=blue>$baseurl</a>";
      } else {
        echo "<br><b>There was an error:</b><br><hr>";
        $result = pg_query($dbh, $outboard_log_table);
	print pg_error();
        $error = true;
      }
    } else {
      echo "<br><b>There was an error:</b><br><hr>";
      $result = pg_query($dbh, $outboard_table);
      print pg_error();
      $error = true;
    }
  }
    if ($error) {
      echo "<hr><br>";
      echo "Consult your MySQL documentation for further information ";
      echo "about the above error.<p>";
      echo "Please correct this problem, and then ";
      echo "<a href=\"$baseurl\" class=blue>Click Here</a>.";
    }
  }
   
} else { // Show instructions and Install button  ?>

Welcome to <b>OutBoard <?php echo $version ?></b>. Since this
is a new installation, this program will automatically install the 
necessary tables in the selected database. Currently, the relevant 
configured options are as follows:
<ul>
  <li>Database: <b><?php echo $db ?> </b></li>
  <li>DB Username: <b><?php echo $dbuser ?> </b></li>
  <li>DB Password: <b><?php echo $dbpass ?> </b></li>
</ul>

If the above information is not correct, please edit the <b>config.php</b>
script and <a href="<?php echo $baseurl ?>" class=blue>Click Here</a> 
to reload this page.<p>

If everything looks OK, enter the requested information and then click the 
button below:<p>
<FORM ACTION="<?php echo $baseurl ?>" METHOD=post>
Enter the admin's username: 
<INPUT TYPE=text NAME=adminuser size=10 maxlength=50><br>
Enter a password for this user: 
<INPUT TYPE=text NAME=adminpass size=10 maxlength=50><br>
Enter the screen name for this user: 
<INPUT TYPE=text NAME=adminname size=10 maxlength=50><p>
<CENTER>
<INPUT TYPE=submit NAME=submit VALUE="Install Tables">
</CENTER>
</FORM>
  
<?php } ?>

</TD></TR>
</TABLE>
</TD></TR>
</TABLE><p>
</BODY>
</HTML>

<?php 
      exit; // Exit so that the rest of the 
	    // outboard.php script does not run
?>
