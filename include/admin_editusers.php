<?php

// admin_editusers.php
//
// Shows a list of the users on the OutBoard, and lets the admin user
// edit or delete them. Included from admin.php script at appropriate
// time.
// 
// 2005-02-16  richardf - updated to work with OutBoard 2.0
// 2002-04-11  richardf - changed mt_rand() for $unique to uniqid("")
// 2001-06-11  Richard F. Feuerriegel  (richardf@acesag.auburn.edu)

if (! $ob->isAdmin()) { exit; }

$mainscreen = false;  // We are not on the main admin screen

$unique = uniqid("");  // Hack to get around I.E. caching. Trys to make
		       // sure that some URLs are different (enough).

$header = "
  <tr>
  <th>Del.</th>
  <th>Name</th>
  <th>User ID</th>
  <th>Options</th>
  <th>Edit</th>
  </tr>
";

?>

<SCRIPT Language="JavaScript1.2">
  function deleteConfirm(user,id,unique) {
    if (confirm("Delete OutBoard user "+user+"?")) {
      mylocation =
	"<?php echo $baseurl ?>?adminscreen=1&deletethisuser="
	  + id 
	  +"&unique=<?php echo $unique ?>";
      self.location = mylocation;
    }
  }
</SCRIPT>

<table border=0 cellpadding=1 cellspacing=1>
  <tr><th colspan=4 align=center><b>Editing Users</b></th></tr>
  <?php echo $header ?>
<?php

$count = 0;
if ($ob->getData()) {
  while ($row = $ob->getRow()) {
    $count++;
    if ($count % 15 == 0) { echo $header; }
    $rowid = $row['rowid'];
    echo "<tr>";
    echo "<td align=center>";
    if (! preg_match("/\<ADMIN\>/",$row['options'])) {
      $userid = addslashes($row['userid']);
      echo "<a href=\"javascript:deleteConfirm('$userid','$rowid')\">"
	   ."<img src=$image_dir/delete.gif border=0></a>";
    } else {
      echo "*";
    }
    echo "</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['userid']."</td>";
    echo "<td>";
    if (preg_match("/\<READONLY\>/",$row['options'])) {
      echo "RO ";
    }
    if (preg_match("/\<ADMIN\>/",$row['options'])) {
      echo "Admin";
    }
    echo "</td>";
    echo "<td align=center>";
    echo "<a href=\"${baseurl}?adminscreen=1&editthisuser=$rowid\">"
	 ."<img src=$image_dir/edit.gif border=0></a>";
    echo "</td>";
    echo "</tr>\n";
  }
} else {
  // This shouldn't happen because we have an admin user
  echo "There are no users at this time.<p>";
}

?>

</table>


<FORM action="<?php echo $baseurl ?>?editusers=1" method=post>
  <table border=0 align=center>
  <tr>
      <td align=center>
      <input type=submit name=cancel value="Cancel">
      </td>
  </tr>
  </table>
<FORM>

<p>
<center>* Cannot delete admins</center>

