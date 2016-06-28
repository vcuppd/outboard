<?php

// create_tables.php
//
// 2005-09-09	richardf - changed rowid def. to be MySQL 4.1 compatible.
// 2002-04-11	richardf - fixed CREATE statements to use $config variables
// 2001-06-08	Richard F. Feuerriegel (richardf@acesag.auburn.edu)
//	- Initial creation
//

$outboard_table = "
CREATE TABLE $table (
   rowid int(10) unsigned NOT NULL auto_increment,
   userid varchar(50) NOT NULL,
   password varchar(50),
   back datetime DEFAULT '1980-01-01 00:00:00' NOT NULL,
   remarks varchar(100),
   name varchar(30) NOT NULL,
   options varchar(100),
   last_change varchar(100),
   session varchar(50),
   timestamp timestamp,
   PRIMARY KEY (rowid),
   UNIQUE userid (userid)
) ";

$outboard_log_table = "
CREATE TABLE $logtable (
   rowid int(10) unsigned NOT NULL auto_increment,
   userid varchar(50) NOT NULL,
   back datetime DEFAULT '1980-01-01 00:00:00' NOT NULL,
   remarks varchar(100),
   name varchar(30) NOT NULL,
   last_change varchar(100),
   timestamp timestamp,
   PRIMARY KEY (rowid),
   KEY userid (userid)
) ";

?>
