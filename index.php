<?php

// index.php
//
// Launches the main OutBoard window.
//
// 2005-02-16, richardf - changed to work with OutBoard 2.0
// 2000-08-30, Richard F. Feuerriegel (richardf@acesag.auburn.edu)

include_once("lib/OutboardConfig.php");

$conf = new OutboardConfig();
$version = $conf->getConfig('version');
$baseurl = $conf->getConfig('progname');

?>

<HTML>
<HEAD>
<TITLE>In/Out Office Board</TITLE>
</HEAD>
<BODY BGCOLOR=#FFFFFF TEXT=#000000>

<Script Language="JavaScript1.2">

  top.outBoardWin = window.open('<?php echo $baseurl ?>?launch=1&noupdate=1', '<?php echo $conf->getConfig('table') ?>_outboard','dependent=no,width=500,height=480,screenX=100,screenY=100,titlebar=yes,resizable=yes,scrollbars=yes');
  top.outBoardWin.focus();
  document.write("The in/out board window has been launched.<p>");
  document.write("This window is no longer needed.");

  self.location = "<?php echo $conf->getConfig('advertisement') ?>";

  console.log("connected");

</Script>

<noscript>
You need a JavaScript 1.2 compliant browser to run this application.<p>
</noscript>

</BODY>
</HTML>
