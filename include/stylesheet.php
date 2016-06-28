<?php

// Simple function to find out what Operating System the user has. This is
// important because the Helvetica font on Unix is a lot smaller than the
// Helvetica/Arial font on MS-Windows, and thus we have to make up for it.
Function get_os() {
  Global $HTTP_USER_AGENT;
  $user_agent = $HTTP_USER_AGENT;
  $os = "win";
  if(preg_match("/Win/",$user_agent)) { $os = "win"; }
  elseif(preg_match("/linux/i",$user_agent)) { $os = "unix"; }
  elseif(preg_match("/unix/i",$user_agent)) { $os = "unix"; }
  return $os;
}

// Set the base font size to 10 on windows and 12 on Unix/Linux
if (get_os() == "win") { $bfs = $windows_bfs; } else { $bfs = $unix_bfs; }

if (get_os() == "win") { 
  $font_family = $windows_font_family;
} else {
  $font_family = $unix_font_family;
}

?>

<STYLE>
  body { font-family: <?php echo $font_family ?>; 
         font-size: <?php echo $bfs ?>pt; 
	 background-color: <?php echo $body_bg ?>; }
  th { font-family: <?php echo $font_family ?>; 
       font-size: <?php echo $bfs ?>pt; 
       font-weight: bold; }
  td { 
     font-family: <?php echo $font_family ?>; 
     font-size: <?php echo $bfs ?>pt; 
     color: <?php echo $td_text ?>;
     background-color: <?php echo $td_bg ?>; 
  }
  td.zebra1 {
	  background-color: <?php echo $td_zebra1 ?>;
  }
  td.zebra2 {
	  background-color: <?php echo $td_zebra2 ?>;
  }
  tr {
     font-family: <?php echo $font_family ?>; 
     font-size: <?php echo $bfs ?>pt; 
  }
  tr.norm {
     background-color: <?php echo $td_bg ?>; 
  }
  td.user {
     background-color: <?php echo $td_user_bg ?>; 
  }
  td.small {
     font-size: <?php echo $bfs - 2 ?>pt; 
     background-color: <?php echo $body_bg ?>; 
  }
  td.header { 
     font-size: <?php echo $bfs ?>pt; 
     font-weight: bold; 
     background-color: <?php echo $body_bg ?>;
  }
  td.headernb { 
     font-size: <?php echo $bfs ?>pt; 
     background-color: <?php echo $body_bg ?>;
  }
  td.back { background-color: <?php echo $td_lines ?>; }
  A:link  { font-family: <?php echo $font_family ?>;  
	    text-decoration: none; 
	    color: <?php echo $link_text ?>}
  .blue { color: #0000FF; }
  .nobr { white-space: nowrap; }
  A:visited  { font-family: <?php echo $font_family ?>;
	       text-decoration: none; 
	       color: <?php echo $link_text ?>}
  A:hover  { font-family: <?php echo $font_family ?>; 
             text-decoration: underline; 
	     color: <?php echo $link_text ?>}
</STYLE>
