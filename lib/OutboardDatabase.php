<?php

/**
 * OutboardDatabase.php
 *
 * Controls all database access for the OutBoard.
 *
 * 2005-02-15	Richard F. Feuerriegel	(richardf@aces.edu)
 *	- Initial creation
 *
 **/

require_once("lib/OutboardConfig.php");

Class OutboardDatabase extends OutboardConfig {

var $dbh = null;           // Database handle
var $hostIP = null;        // IP address of the user's computer
var $operatingUser = null; // The username of the person using the OutBoard
var $readonly = null;      // Boolean. True if the board is in read-only mode.
var $admin = null;         // Boolean. True if the user is an OutBoard administrator.
var $session = null;       // The current session value used for authentication later
var $result = null;	   // The current query result handle


Function OutboardDatabase() {
  // Call the superclass constructor
  $this->OutboardConfig();
  
  if (! function_exists("mysql_connect")) {
	  trigger_error("The MySQL libraries are not installed.");
  }

  // Open the database connection
  $this->dbh = @mysql_connect($this->getConfig('dbhost'),
			      $this->getConfig('dbuser'),
			      $this->getConfig('dbpass'));
  if (! $this->dbh) {
    trigger_error("Unable to connect to the database server.");
  }
  if (! @mysql_select_db($this->getConfig('db'),$this->dbh)) {
    trigger_error("Unable to open the OutBoard database.");
  }
  $this->hostIP = $_SERVER['REMOTE_ADDR'];
  $this->setReadonly(false);
  $this->setAdmin(false);
  $this->autoLogoutIdlers();
}


Function getOperatingUser() { return $this->operatingUser; }
Function setOperatingUser($username) { $this->operatingUser = $username; }

Function isReadonly() { return $this->readonly; }
Function setReadonly($boolean) { $this->readonly = $boolean; }

Function isAdmin() { return $this->admin; }
Function setAdmin($boolean) { $this->admin = $boolean; }

Function isChangeable($userid) {
  $level  = $this->getConfig('allow_change');
  $opuser = $this->getOperatingUser();
  if     ($level == "all") { return true; }
  elseif ($level == "user_only"  && $userid == $opuser) { return true; }
  elseif ($level == "admin_only" && $this->isAdmin() )  { return true; }
  elseif ($level == "user_admin" 
	  && ($this->isAdmin() || $userid == $opuser))  { return true; }
  else { return false; }
}


Function getSession($session_cookie) {
  $table = $this->getConfig('table');
  if ($session_cookie == "") { return null; }
  $stmt = "SELECT userid,name,options FROM $table WHERE session = '$session_cookie'";
  $this->_query($stmt);
  if ($this->numRows() != 1) {
    return null;
  } else {
    $row = $this->getRow();
    $this->setOperatingUser($row['userid']);
    $this->setReadonly(preg_match("/\<READONLY\>/",$row['options']));
    $this->setAdmin(preg_match("/\<ADMIN\>/",$row['options']));
    return $this->getOperatinguser();
  } 
}


Function setSession($session = "NONE") {
  $table = $this->getConfig('table');
  if (! $userid = $this->getOperatingUser()) { return false; }
  if ($session == "NONE") {
    mt_srand((double)microtime()*1000000); 
    $session = mt_rand(1,10000000) . uniqid(""); 
    $this->session = $session;
  }
  $stmt = "UPDATE $table SET session='$session' WHERE userid='$userid'";
  if ($this->_query($stmt)) {
    return $session;
  } else {
    return false;
  }
}


Function checkPassword($username,$password) {
  $table = $this->getConfig('table');
  $username = addslashes($username);
  $password = addslashes($password);
  $stmt = "SELECT userid FROM $table WHERE userid='$username' and password=password('$password')";
  if ($this->_query($stmt)) {
    if ($this->numRows() == 1) {
      $row = $this->getRow();
      $this->setOperatingUser($row['userid']);
      return $this->setSession(); 
    }
  }
  return false;
}


Function getLogStartDate() {
  $logtable = $this->getConfig('logtable');
  $stmt = "SELECT timestamp,"
	 ."date_format(timestamp, '%Y-%m-%d') as changedate "
	 ."FROM $logtable ORDER BY rowid ASC limit 1";
  if ($this->_query($stmt)) {
    $row = $this->getRow();
    return $row['changedate'];
  } else {
    return null;
  }
}

Function getLogEndDate() {
  $logtable = $this->getConfig('logtable');
  $stmt = "SELECT timestamp,"
	 ."date_format(timestamp, '%Y-%m-%d') as changedate "
	 ."FROM $logtable timestamp ORDER BY rowid DESC limit 1";
  if ($this->_query($stmt)) {
    $row = $this->getRow();
    return $row['changedate'];
  } else {
    return null;
  }
}


// Gets all the rows/data from the log table for a specific user 
// and data range. $start and $end are in the form 'yyyy-mm-dd'.
Function getLogData($userid,$start,$end) {
  $logtable = $this->getConfig('logtable');
  $stmt =
    "SELECT rowid,userid,back,remarks,name,timestamp,
            date_format(back, '%H:%i') as backtime,
            date_format(timestamp, '%Y-%m-%d') as changedate,
            date_format(timestamp, '%H:%i') as changetime,
            date_format(timestamp, '%d') as day,
            unix_timestamp(timestamp) as timeinseconds
     FROM $logtable
     WHERE timestamp >= '$start'
       AND timestamp <= '$end 23:59:59'
       AND userid = '$userid'
     ORDER BY timestamp
    ";
  if ($this->_query($stmt)) {
    return true;
  } else {
    return false;
  }
}

// Gets the log data and converts it into an array;
Function getLogDataArray($userid,$start,$end) {
  if ($this->getLogData($userid,$start,$end)) {
    if (! $this->numRows()) { return null; }
    $ld = Array();
    while($row = $this->getRow()) {
      $ld[] = $row;
    }
    return $ld;
  } else {
    return null;
  }
}


Function getNames() {
  $table = $this->getConfig('table');
  $stmt = "SELECT DISTINCT userid,name FROM $table "
         ."WHERE options is null or options NOT LIKE '%<READONLY>%' ORDER BY name";
  $this->_query($stmt);
  if (! $this->numRows()) { return null; }
  $userArray = Array();
  while($row = $this->getRow()) {
    $userArray[$row['userid']] = $row['name'];
  } 
  return $userArray;
}


// Gets all the rows/data from the main Outboard table
Function getData() {
  $table = $this->getConfig('table');
  $stmt = "select rowid, userid, name, options, unix_timestamp(back) as back, "
         ."remarks, last_change, date_format(timestamp, '%m/%d, %l:%i %p') as timestamp "
         ."from $table order by name";
  if ($this->_query($stmt)) {
    return true;
  } else {
    return false;
  }
}

// Moves the dots to Out after a specified idle time (in seconds).
Function autoLogoutIdlers() {
  $seconds = abs(floor($this->getConfig('max_idle_seconds')));
  if ($seconds > 0) {
    $table = $this->getConfig('table');
    $out = $this->getConfig('out');
    $stmt = "UPDATE $table "
	   ."SET back='$out',last_change='auto-logout,0.0.0.0' "
	   ."WHERE back != '$out' AND "
	   ."(unix_timestamp(now()) - unix_timestamp(timestamp)) > $seconds";
    $this->_query($stmt);
  }
}

// Gets the data on a single person
Function getDataByID($rowid) {
  $table = $this->getConfig('table');
  $rowid = addslashes($rowid);
  $stmt = "select rowid, userid, password, name, options, "
         ."remarks, last_change, date_format(timestamp, '%m/%d, %l:%i %p') as timestamp "
         ."from $table where rowid = '$rowid'";
  if ($this->_query($stmt)) {
    return true;
  } else {
    return false;
  }
}

Function isBoardMember($userid) {
  $table = $this->getConfig('table');
  $userid = addslashes($userid);
  $stmt = "select rowid from $table where userid = '$userid'";
  $this->_query($stmt);
  if ($this->numRows() == 1) {
    return true;
  } else {
    return false;
  }
}


Function setDotIn($userid) {
  return $this->_moveDot($userid,$this->getConfig('in')); 
}


Function setDotOut($userid) {
  return $this->_moveDot($userid,$this->getConfig('out')); 
}


Function setDotTime($userid,$hour) {
  $current = getdate();
  $return_datetime = mktime($hour,'00','00',$current['mon'],$current['mday'],$current['year']);
  $back = date('Y-m-d H:i:s',$return_datetime);
  return $this->_moveDot($userid,$back); 
}


Function _moveDot($userid,$back) {
  if ($this->isReadonly()) { return true; }
  if (! $this->isChangeable($userid)) { return true; }
  $table = $this->getConfig('table');
  $hostIP = $this->hostIP;
  $operatingUser = $this->operatingUser;
  $stmt = "update $table "
	 ."set back='$back',last_change='$operatingUser,$hostIP' "
	 ."where userid = '$userid'";
  if($this->_query($stmt)) {
    return $this->_log($userid);
  }
}


Function setRemarks($userid,$remarks) {
  if ($this->isReadonly()) { return true; }
  if (! $this->isChangeable($userid)) { return true; }
  $table = $this->getConfig('table');
  $hostIP = $this->hostIP;
  $operatingUser = $this->operatingUser;
  $remarks = trim($remarks);
  $stmt = "update $table "
	 ."set remarks='".addslashes($remarks)."',last_change='$operatingUser,$hostIP' "
	 ."where userid = '$userid'";
  if($this->_query($stmt)) {
    return $this->_log($userid);
  }
}


Function _log($userid) {
  $table = $this->getConfig('table');
  $logtable = $this->getConfig('logtable');
  $stmt = "select * from $table where userid = '$userid'";
  $this->_query($stmt); 
  $row = $this->getRow();
  $r_remarks = addslashes($row['remarks']);
  $r_userid = $row['userid'];
  $r_back = $row['back'];
  $r_name = $row['name'];
  $r_last_change = $row['last_change'];
  $stmt = "INSERT INTO $logtable (userid,back,remarks,name,last_change) "
         ."VALUES ('$r_userid','$r_back','$r_remarks','$r_name','$r_last_change')";
  return $this->_query($stmt);
}


// Edits an existing user if $rowid is set; adds otherwise.
Function saveUser($rowid,$name,$pass,$visible,$options) {
  $table = $this->getConfig('table');
  $rowid = addslashes($rowid);
  $name = addslashes($name);
  $pass = addslashes($pass);
  $visible = addslashes($visible); // name
  $options = addslashes($options);
  if ($rowid) {
    $this->getDataByID($rowid);
    $row = $this->getRow();
    // Only update the password if it changed on the form.
    if ($row['password'] != $pass) {
      $password = "password('$pass')";
    } else {
      $password = "'$pass'";
    }
    $stmt = "UPDATE $table SET "
	   ."userid='$name',password=$password,"
	   ."name='$visible',options='$options' "
	   ."WHERE rowid='$rowid'";
  } else {
    $stmt = "INSERT INTO $table (userid,password,name,options) "
	   ."VALUES ('$name',password('$pass'),'$visible','$options')";
  }
  return $this->_query($stmt);
}


// Delete the user from the OutBoard
Function deleteUser($rowid) {
  $table = $this->getConfig('table');
  $rowid = addslashes($rowid);
  if (! $rowid) { return null; }
  $stmt = "DELETE FROM $table WHERE rowid='$rowid'";
  return $this->_query($stmt);
}


Function _query($stmt) {
  if (! $stmt) { return false; }
  if ($this->result = mysql_query($stmt)) {
    return true;
  } else {
    trigger_error("Error in database query.");
    //print(mysql_error());
    //print("stmt = ".$stmt);
    return false;
  }
}

Function numRows() {
  if (! $this->result) { return null; }
  return mysql_num_rows($this->result);
}

Function getRow() {
  if (! $this->result) { return null; }
  if ($row = mysql_fetch_array($this->result)) { 
    return $row;
  } else {
    return null;
  } 
}

}

?>
