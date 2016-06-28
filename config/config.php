<?php

// this->config.php
//
// This is the this->configuration file for the OutBoard program.
//
// 2012-04-25, feuerri - 2.2.6 - Bug fix release.
// 2009-05-29, feuerri - 2.2.5 - Bug fix release.
// 2007-02-16, richardf - 2.2.4 - added custom time for cookies
// 2005-03-04, richardf - v2.2 - Added allow_change variable
// 2005-02-19, richardf - v2.1 - some default values altered
// 2005-02-16, richardf - updated to work with OutBoard 2.0
// 2001-06-08, richardf - Added automatic installation variable
// 2000-08-31, Richard F. Feuerriegel (richardf@acesag.auburn.edu)
// 	- Initial creation

// Set the error reporting level.
error_reporting(0); 			// Change to E_ALL for debugging
ini_set("display_errors",0);  		// Change to 1 for debugging
ini_set("display_startup_errors",0); 	// Change to 1 for debugging

// Set the default timezone. This is useful if the program is running on
// a server that is in a different TZ than the users. For a list of timezones,
// see http://us.php.net/manual/en/timezones.php .
//date_default_timezone_set('US/Central');

// Database this->configuration
$this->config['dbhost']   = "localhost";// hostname of the DB server
$this->config['db']       = "outboard";	// database that contains tables 
$this->config['dbuser']   = "outuser";	// username for above database 
$this->config['dbpass']   = "outpass";	// password for above username 
$this->config['table']    = "outboard";	// main table for the outboard 
$this->config['logtable'] = "outboard_log";  // logging table for changes

// Automatic Installation of database tables. Set this variable to false
// once the automatic installation procedure is completed.
$this->config['installtables'] = true; 	// true or false (no quotes)

// Sets the authentication method for the OutBoard. 
// Options: 
//    internal
//    basic       
$this->config['authtype'] = "internal";

// Sets the temporary directory for writing HTML report file. The directory
// must be readable and writable by the web server process owner.
// NOTE: This path MUST end in a trailing slash.
$this->config['temp_dir'] = "/tmp/";

// User level required to change dots and remarks.
// Options:
//    all         - Any user (non-readonly) can change anyone's info
//    user_only   - Only the user can change his info (not even the admin)
//    admin_only  - Only the admins can change anyone's info
//    user_admin  - Only the user (and admins) can change a user's info
$this->config['allow_change'] = "all";

// The Title of the board
$this->config['board_title'] = "Partnership for People with Disabilities";

// Where to send the launch window after it opens the outboard
$this->config['advertisement'] = "http://outboard.sourceforge.net/";

// The time period (in weeks) for the timeclock reports
$this->config['timeperiod'] = 2;  

// The date from which to start calculating periods (YYYY-MM-DD format)
$this->config['periodstart'] = "2016-01-01";  

// The URL of the HTML->PDF converter program for use in the timesheet
// report. Leave this blank if you don't have one available; the timesheet
// report will then be displayed on screen in HTML format.
$this->config['pdf_writer'] = "";  
$this->config['pdf_writer_key'] = "";  

// URL for Schedule page. If set, the link (named below) will appear
// at the bottom left of the board.
$this->config['schedule_url'] = "";  
$this->config['schedule_name'] = "";  


//------------------------------------------------------------------
// END OF NORMAL CONFIGURATION OPTIONS

// Number of seconds to wait before reloading the screen
$this->config['reload_sec']  = 300;  // normal reload while in view only mode
$this->config['update_sec']  = 120;  // update screen change to view only
$this->config['night_sec']   = 3600; // after hours reload time

// The number of idle seconds before the system automatically moves out dots
// Setting this to 0 (zero) disables automatic logout.
$this->config['max_idle_seconds']  = 43200;  // 43200 seconds = 12 hours

// The number of seconds that the session cookie will last in the browser
// Setting this to 0 (zero) means that the cookie with be removed when the
// browser closes.
$this->config['cookie_time_seconds']  = 86400;  // 86400 seconds = 24 hours

// Image variables
$this->config['image_dir']    = "image";
$this->config['empty_image']  = "w.gif";
$this->config['dot_image']    = "b.png";
$this->config['out_image']    = "g.png";
$this->config['in_image']     = "gr.png";
$this->config['change_image'] = "change-button.png";
$this->config['view_image']   = "view-button.png";
$this->config['right_arrow']  = "right-arrow.gif";

// Colors used in the style sheet
$this->config['body_bg']     = "#D0D0D0"; // background color of the main window
$this->config['td_bg']       = "#FFFFFF"; // background color of the main table cells
$this->config['td_zebra1']   = "#FFFFFF"; // background color of alternating rows
$this->config['td_zebra2']   = "#EFEFEF"; // background color of alternating rows
$this->config['td_user_bg']  = "#d8bfd8"; // background color when editing user's info
$this->config['td_text']     = "#000000"; // text color in the table cells
$this->config['td_lines']    = "#E9E9E9"; // color of the "lines" in the outboard
$this->config['link_text']   = "#000000"; // color of the link text (in the remarks)

// Font faces and sizes (bff = base font face, bfs = base font size)
$this->config['windows_font_family'] = "Arial, Helvetica, sans-serif";
$this->config['unix_font_family']    = "Helvetica, Arial, sans-serif";
$this->config['windows_bfs']         = 10;
$this->config['unix_bfs']            = 12;    

// The maximum "length" of the visible portion of remarks before the arrow
// is added to the right, and the rest cut off from showing.
$this->config['max_visible_length'] = 20;

// The format of the date shown at the top of the page
$this->config['date_format'] = "h:ia D, M jS";

// Reprint the header every X number of lines.
$this->config['reprint_header'] = 13;

// Alternating colors for every N of user rows. 0 disables. An integer
// greater than 0 will cause "zebra striping" where N rows will be one
// color, and N rows will be another color. Colors are set in td_zebra1
// and td_zebra2 above.
$this->config['zebra_stripe'] = 0;

// The in and out datastamps. Must be valid for the database. 
$this->config['in']  = '1980-01-01 00:00:00';
$this->config['out'] = '2030-01-01 00:00:00';

//----------------------------------------------------------------------
// NO NEED TO CHANGE THINGS FROM THIS POINT DOWN

// The version number of this program
$this->config['version'] = "2.2.6";            // Don't change this
$this->config['version_date'] = "2012";      // Don't change this

// The name of the main outboard program
$this->config['progname'] = "outboard.php";  // Don't change this

?>
