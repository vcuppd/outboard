<?php

// This file defines a table of relative character widths. It is needed for
// the remarks field since we want to show as much of the remark as possible
// without having it wrap in the table cell. Since some letters (in a non
// proportional font) are wider than others, we must cut off the text sooner
// if wider letters are used.
// 
// 2000-09-01, Richard F. Feuerriegel (richardf@acesag.auburn.edu)


// For Helvetica or Arial fonts

$width['a'] = 1;   $width['A'] = 1.3;
$width['b'] = 1;   $width['B'] = 1.3;
$width['c'] = 1;   $width['C'] = 1.3;
$width['d'] = 1;   $width['D'] = 1.3;
$width['e'] = 1;   $width['E'] = 1.3;
$width['f'] = 0.7; $width['F'] = 1.3;
$width['g'] = 1;   $width['G'] = 1.3;
$width['h'] = 1;   $width['H'] = 1.3;
$width['i'] = 0.3; $width['I'] = 0.3;
$width['j'] = 0.5; $width['J'] = 1.3;
$width['k'] = 1;   $width['K'] = 1.3;
$width['l'] = 0.4; $width['L'] = 1.3;
$width['m'] = 1.5; $width['M'] = 1.5;
$width['n'] = 1;   $width['N'] = 1.3;
$width['o'] = 1;   $width['O'] = 1.3;
$width['p'] = 1;   $width['P'] = 1.3;
$width['q'] = 1;   $width['Q'] = 1.3;
$width['r'] = 0.8; $width['R'] = 1.3;
$width['s'] = 1;   $width['S'] = 1.3;
$width['t'] = 0.5; $width['T'] = 1.3;
$width['u'] = 1;   $width['U'] = 1.3;
$width['v'] = 1;   $width['V'] = 1.3;
$width['w'] = 1.3; $width['W'] = 1.6;
$width['x'] = 1;   $width['X'] = 1.3;
$width['y'] = 1;   $width['Y'] = 1.3;
$width['z'] = 1;   $width['Z'] = 1.3;

$width[' '] = 1;
$width[':'] = 0.3;
$width['.'] = 0.6;
$width[','] = 0.5;
$width['_'] = 1;
$width[';'] = 0.3;
$width['&'] = 1.1;
$width['('] = 0.5;
$width[')'] = 0.5;
$width['!'] = 0.2;
$width['@'] = 1.7;
$width['^'] = 0.6;
$width['*'] = 0.5;
$width['-'] = 0.9;
$width['+'] = 0.6;
$width['\''] = 0.3;
$width['\"'] = 0.3;
$width['/'] = 0.8;

// This function returns a shortened version of the input string, based
// on the max_width variable. The function adds up the widths of each
// character in the string, and stops making the new string once the
// max_width is reached.

Function trim_visible( $input_string, $max_width ) {
  Global $width;
  $length = strlen($input_string);
  $total = 0;
  $last_to_show = $length;
  for ($i=0;$i<$length;$i++) {  
    if (isset($width[$input_string[$i]])) {
      $char_width = $width[$input_string[$i]];
    } else {
      $char_width = 1;
    }
    if (! ($char_width > 0) ) { $char_width = 1; }
    //DEBUG  echo "[" . $input_string[$i] . "] = $char_width<br>";
    $total += $char_width;
    if ($total >= $max_width) { $last_to_show = $i - 1; break;}
  }
  //DEBUG  echo "[length = $length, total = $total, shown = $last_to_show] ";
  return substr($input_string,0,$last_to_show);
}

?>
