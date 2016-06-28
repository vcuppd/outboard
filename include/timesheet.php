<?php
// Timesheet generation script for an HTML->PDF converter
//
// 2005-02-17 richardf - introduced and updated for OutBoard 2.0
// 2001-03-16 Richard F. Feuerriegel (richardf@acesag.auburn.edu)


$date = date("l, F dS Y, h:i:s A");
$style_sheet = join('',file("include/reportstylesheet.php"));
$filename = "timesheet_for_${userid}_by_${username}";
$tmpfname = $ob->getConfig('temp_dir').$filename;
$fd = fopen($tmpfname,"w");
$summary_timeclock = $timearray['summary'];
$board = $ob->getConfig('board_title');

fputs($fd,"<HTML>");
fputs($fd,"<HEAD>");
fputs($fd,$style_sheet);
fputs($fd,"<TITLE>$board Timesheet for $fullname</TITLE>");
fputs($fd,"</HEAD>");
fputs($fd,"<BODY>");

fputs($fd,"<CENTER>");
fputs($fd,"<TABLE BORDER=0 WIDTH=100% ALIGN=CENTER>");
fputs($fd,"<TR>");
fputs($fd,"<TD ALIGN=LEFT WIDTH=70%>");
fputs($fd,"<H3>$board<br>Timeclock Report for: <u>$fullname</u></H3>");
fputs($fd,"<H4>$date</H4>");
fputs($fd,"</TD>");
fputs($fd,"<TD ALIGN=LEFT VALIGN=TOP>");
fputs($fd,"<b>Pay period:</b><br>Start: $paystart<br>End: $payend");
fputs($fd,"</TD>");
fputs($fd,"</TR>");
fputs($fd,"</TABLE>");
fputs($fd,"</CENTER>");

fputs($fd,"<p>");

fputs($fd,"<CENTER>");
fputs($fd,"<TABLE BORDER=0 width=100% ALIGN=CENTER>");
fputs($fd,"<TR><TD VALIGN=TOP COLSPAN=2>&nbsp;</TD></TR>");
fputs($fd,"<TR>");
  fputs($fd,"<TD ALIGN=LEFT VALIGN=TOP WIDTH=25%>");
  fputs($fd,$summary_timeclock);
  fputs($fd,"</TD>");
  fputs($fd,"<TD ALIGN=LEFT VALIGN=TOP WIDTH=75%>");
  fputs($fd,"<h2>Total hours calculated: <u>".sprintf("%4.2f",$totalHoursWorked)."</u></h2>");
  fputs($fd,"<h2>Hours to be paid: <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></h2>"); 
  fputs($fd,"<p>&nbsp;<p>"); 
  fputs($fd,"Employee Signature:<p>________________________________________________ Date:_____________<p>");
  fputs($fd,"&nbsp;<p>");
  fputs($fd,"Supervisor Signature:<p>________________________________________________ Date:_____________<p>");
  fputs($fd,"<p>&nbsp;<p>"); 
  fputs($fd,"NOTES:<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"______________________________________________________________________<p>");
  fputs($fd,"</TD>");
fputs($fd,"</TR>");
fputs($fd,"<TR><TD VALIGN=TOP COLSPAN=2>&nbsp;</TD></TR>");
fputs($fd,"</TABLE>");
fputs($fd,"</CENTER>");

/* This doesn't look right in the PDF
fputs($fd,"<TABLE BORDER=0 width=100%>");
fputs($fd,"<TR>");
  fputs($fd,"<TD VALIGN=TOP ALIGN=CENTER COLSPAN=2>");
  //fputs($fd,"<!--NewPage-->\n");
  //fputs($fd,"<HR class=PAGE-BREAK>\n");
  fputs($fd,$details_timeclock);
  fputs($fd,"</TD>");
fputs($fd,"</TR>");
fputs($fd,"</TABLE>");
*/


fputs($fd,"</BODY>");
fputs($fd,"</HTML>");
fclose($fd);

if ($url = $ob->getConfig('pdf_writer')) {
  $key =  $ob->getConfig('pdf_writer_key');
  header("Location: ${url}?filename=".basename($tmpfname)."&key=${key}");
} else {
  echo join('',file($tmpfname));
}

?>
